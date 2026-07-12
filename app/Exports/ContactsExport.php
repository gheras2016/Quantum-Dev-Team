<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContactsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Contact::query()->latest();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Read', 'Date'];
    }

    /**
     * @param  Contact  $contact
     */
    public function map($contact): array
    {
        return [
            $contact->id,
            $contact->name,
            $contact->email,
            $contact->phone,
            $contact->subject,
            $contact->message,
            $contact->status,
            $contact->is_read ? 'Yes' : 'No',
            $contact->created_at?->format('Y-m-d H:i'),
        ];
    }
}
