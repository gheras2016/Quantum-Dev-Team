<?php

namespace App\Exports;

use App\Models\ProjectRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectRequestsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return ProjectRequest::query()->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'WhatsApp', 'Project Type', 'Timeline', 'Description', 'Status', 'Date'];
    }

    /**
     * @param  ProjectRequest  $request
     */
    public function map($request): array
    {
        return [
            $request->id,
            $request->name,
            $request->email,
            $request->whatsapp,
            $request->project_type,
            $request->timeline,
            $request->description,
            $request->status,
            $request->created_at?->format('Y-m-d H:i'),
        ];
    }
}
