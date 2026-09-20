<?php

return [
    'title' => 'Backup & Restore',

    'export_title' => 'Export a full backup',
    'export_desc' => 'Download a complete snapshot of the database (all content, users and settings) as a single JSON file. Keep it somewhere safe — it is all you need to fully restore the site.',
    'includes' => 'This backup includes :count tables.',
    'export_note' => 'The file contains sensitive data (including hashed passwords). Store it privately and never commit it to Git.',
    'railway_tip' => "Railway's server disk is temporary, so the file is streamed straight to your computer and is never stored on the server.",
    'export_button' => 'Download backup now',

    'restore_title' => 'Restore from a backup',
    'restore_desc' => 'Upload a backup file to restore the database to that snapshot.',
    'restore_warning' => 'Warning: restoring REPLACES all current data with the contents of the file and cannot be undone. Export a fresh backup first.',
    'file_label' => 'Backup file (.json)',
    'confirm_label' => 'I understand this will overwrite all current data.',
    'restore_button' => 'Restore now',

    'import_success' => 'Backup restored successfully: :tables tables, :rows rows.',
    'import_failed' => 'Restore failed: :error',
    'invalid_file' => 'Invalid file. Please upload a JSON file produced by this page.',
];
