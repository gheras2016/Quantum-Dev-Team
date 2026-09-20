<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Full-database export / restore, driver-agnostic (MySQL in production,
 * SQLite locally). The snapshot is a single JSON document so it needs no
 * external tools (no mysqldump) and can be produced and restored entirely
 * from the admin panel — which suits Railway's ephemeral filesystem, where
 * the file is streamed straight to the browser and never kept on the server.
 *
 * Note: this backs up the DATABASE only. Uploaded images live on Cloudinary
 * (persistent) and are referenced by URL inside the data, so they survive a
 * restore without being embedded here.
 */
class BackupService
{
    /** Framework / transient tables that must never be part of a content backup. */
    private const EXCLUDED = [
        'migrations',
        'password_reset_tokens',
        'password_resets',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'personal_access_tokens',
    ];

    /** Bumped if the on-disk format ever changes. */
    private const FORMAT_VERSION = 1;

    /** Rows inserted per query on restore. */
    private const CHUNK = 200;

    /**
     * Build a full snapshot of every application table.
     *
     * @return array{meta: array, data: array<string, array>}
     */
    public function export(): array
    {
        $data = [];

        foreach ($this->tables() as $table) {
            $data[$table] = DB::table($table)->get()
                ->map(fn ($row) => (array) $row)
                ->all();
        }

        return [
            'meta' => [
                'app' => config('app.name'),
                'format' => self::FORMAT_VERSION,
                'generated_at' => now()->toIso8601String(),
                'driver' => DB::getDriverName(),
                'tables' => array_keys($data),
            ],
            'data' => $data,
        ];
    }

    /** Suggested download file name for a fresh export. */
    public function filename(): string
    {
        return 'quantum-backup-'.now()->format('Y-m-d_His').'.json';
    }

    /**
     * Restore a snapshot produced by export(). Destructive: replaces the
     * contents of every table present in the file. The whole restore runs in
     * one transaction (using DELETE, not TRUNCATE, so a failure rolls back
     * cleanly and leaves the current data intact).
     *
     * @param  array{data?: array<string, array>}  $payload
     * @return array{tables:int, rows:int}
     */
    public function import(array $payload): array
    {
        if (! isset($payload['data']) || ! is_array($payload['data'])) {
            throw new \InvalidArgumentException('Invalid backup file: missing "data" section.');
        }

        $existing = $this->tables();
        $driver = DB::getDriverName();
        $tables = 0;
        $rows = 0;

        DB::transaction(function () use ($payload, $existing, $driver, &$tables, &$rows) {
            $this->toggleForeignKeys($driver, false);

            foreach ($payload['data'] as $table => $records) {
                // Only restore tables that still exist in the current schema.
                if (! in_array($table, $existing, true) || ! is_array($records)) {
                    continue;
                }

                DB::table($table)->delete();

                foreach (array_chunk($records, self::CHUNK) as $chunk) {
                    DB::table($table)->insert($chunk);
                    $rows += count($chunk);
                }

                $tables++;
            }

            $this->toggleForeignKeys($driver, true);
        });

        $this->flushCaches();

        return ['tables' => $tables, 'rows' => $rows];
    }

    /** Application tables eligible for backup (all minus framework/transient ones). */
    public function tables(): array
    {
        return array_values(array_filter(
            $this->allTables(),
            fn ($table) => ! in_array($table, self::EXCLUDED, true)
        ));
    }

    private function allTables(): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return array_map(
                fn ($row) => $row->name,
                DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'")
            );
        }

        // MySQL / MariaDB: `SHOW TABLES` returns one column named after the database.
        return array_map(
            fn ($row) => array_values((array) $row)[0],
            DB::select('SHOW TABLES')
        );
    }

    private function toggleForeignKeys(string $driver, bool $on): void
    {
        match ($driver) {
            'sqlite' => DB::statement('PRAGMA foreign_keys = '.($on ? 'ON' : 'OFF')),
            'mysql', 'mariadb' => DB::statement('SET FOREIGN_KEY_CHECKS='.($on ? '1' : '0')),
            default => null, // pgsql/others: not used by this app.
        };
    }

    private function flushCaches(): void
    {
        // Settings and permissions are cached in memory/stores; refresh after a restore.
        Cache::forget('settings.all');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
