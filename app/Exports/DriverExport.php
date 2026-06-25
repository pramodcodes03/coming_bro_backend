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
 * Streams the (already filtered) driver query into an .xlsx file, mirroring the
 * admin driver list (incl. the IP audit columns and live status).
 */
class DriverExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'City',
            'State',
            'Vehicle Number',
            'Online',
            'Verified',
            'Wallet Balance',
            'Registered At',
        ];
    }

    public function map($driver): array
    {
        return [
            $driver->id,
            $driver->full_name ?? '-',
            $driver->email ?? '-',
            trim((string) $driver->country_code . $driver->phone_number) ?: '-',
            $driver->register_ip ?? '-',
            $driver->last_login_ip ?? '-',
            $driver->city ?? '-',
            $driver->state ?? '-',
            $driver->vehicle_number ?? '-',
            $driver->is_online ? 'Online' : 'Offline',
            $driver->document_verification ? 'Verified' : 'Pending',
            number_format((float) $driver->wallet_amount, 2, '.', ''),
            $driver->created_at ? $driver->created_at->format('d M Y, h:i A') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
