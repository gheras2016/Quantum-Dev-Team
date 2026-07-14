<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #1f2937; font-size: 13px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { color: #2563eb; font-size: 22px; font-weight: bold; }
        .title { font-size: 16px; margin-top: 4px; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        td.label { width: 160px; color: #6b7280; font-weight: bold; }
        .desc { margin-top: 20px; }
        .desc h3 { color: #374151; font-size: 14px; margin-bottom: 6px; }
        .desc p { background: #f9fafb; padding: 12px; border-radius: 6px; line-height: 1.6; }
        .footer { margin-top: 30px; font-size: 11px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Quantum Dev Team</div>
        <div class="title">Project Request #{{ $projectRequest->id }}</div>
    </div>

    <table>
        <tr><td class="label">Name</td><td>{{ $projectRequest->name }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $projectRequest->email }}</td></tr>
        <tr><td class="label">WhatsApp</td><td>{{ $projectRequest->whatsapp ?: '—' }}</td></tr>
        <tr><td class="label">Project Type</td><td>{{ $projectRequest->project_type }}</td></tr>
        <tr><td class="label">Timeline</td><td>{{ $projectRequest->timeline }}</td></tr>
        <tr><td class="label">Status</td><td>{{ ucfirst($projectRequest->status) }}</td></tr>
        <tr><td class="label">Submitted</td><td>{{ $projectRequest->created_at?->format('Y-m-d H:i') }}</td></tr>
    </table>

    <div class="desc">
        <h3>Description</h3>
        <p>{{ $projectRequest->description }}</p>
    </div>

    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i') }} — Quantum Dev Team
    </div>
</body>
</html>
