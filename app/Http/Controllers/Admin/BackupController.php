<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Full database backup & restore. Restricted to super admins (a backup file
 * contains every row in the system, including hashed passwords).
 */
class BackupController extends Controller
{
    public function index(BackupService $backup): View
    {
        return view('admin.backup.index', [
            'tables' => $backup->tables(),
        ]);
    }

    /** Stream a fresh full snapshot straight to the browser as a download. */
    public function export(BackupService $backup, ActivityService $activity): StreamedResponse
    {
        $payload = $backup->export();
        $filename = $backup->filename();

        $activity->log('Exported a full database backup');

        return response()->streamDownload(function () use ($payload) {
            echo json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }, $filename, ['Content-Type' => 'application/json']);
    }

    /** Restore the database from an uploaded snapshot (destructive). */
    public function import(Request $request, BackupService $backup, ActivityService $activity): RedirectResponse
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'max:51200'], // up to 50 MB
            'confirm' => ['accepted'],
        ]);

        $payload = json_decode(
            (string) file_get_contents($request->file('backup_file')->getRealPath()),
            true
        );

        if (! is_array($payload) || ! isset($payload['data']) || ! is_array($payload['data'])) {
            return back()->with('error', __('backup.invalid_file'));
        }

        try {
            $stats = $backup->import($payload);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', __('backup.import_failed', ['error' => $e->getMessage()]));
        }

        $activity->log("Restored database from backup ({$stats['tables']} tables, {$stats['rows']} rows)");

        return back()->with('success', __('backup.import_success', $stats));
    }
}
