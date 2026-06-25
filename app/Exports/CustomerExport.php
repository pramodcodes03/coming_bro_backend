<?php

namespace App\Exports;

use Illuminate\Contracts\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Streams the (already filtered) customer query into an .xlsx file. The query
 * is built by the controller from the same filters the list view uses, so the
 * export mirrors exactly what the admin is looking at.
 */
class CustomerExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(private Builder $query)
    {
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Full Name',
            'Email',
            'Phone',
            'Register IP',
            'Last Login IP',
            'Status',
            'Wallet Balance',
            'Registered At',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->full_name ?? '-',
            $customer->email ?? '-',
            trim((string) $customer->country_code . $customer->phone_number) ?: '-',
            $customer->register_ip ?? '-',
            $customer->last_login_ip ?? '-',
            $customer->is_active ? 'Active' : 'Inactive',
            number_format((float) $customer->wallet_amount, 2, '.', ''),
            $customer->created_at ? $customer->created_at->format('d M Y, h:i A') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
