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
 * Streams a wallet-transaction / recharge query into an .xlsx file with the
 * GST breakdown per row. The caller supplies a query whose rows expose the
 * `user_name` and `user_mobile` aliases (resolved via joins) so the mapping
 * stays uniform across the recharge, driver-wallet and user-wallet exports.
 */
class PaymentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'User',
            'User Type',
            'Mobile',
            'Payment Type',
            'Transaction ID',
            'Plan',
            'Order Type',
            'Base Amount',
            'GST %',
            'GST Amount',
            'Total Amount',
            'Note',
            'Date',
        ];
    }

    public function map($row): array
    {
        // Total falls back to the legacy `amount` column for transactions
        // created before GST tracking existed.
        $total = $row->total_amount ?? $row->amount;
        $base = $row->base_amount ?? $row->amount;

        $date = $row->created_date ?? $row->created_at;

        return [
            $row->id,
            $row->user_name ?? 'N/A',
            ucfirst((string) $row->user_type),
            $row->user_mobile ?? '-',
            $row->payment_type ? ucfirst(str_replace('_', ' ', $row->payment_type)) : '-',
            $row->transaction_id ?? '-',
            $row->plan_label ?? '-',
            $row->order_type ? ucfirst(str_replace('_', ' ', $row->order_type)) : '-',
            number_format((float) $base, 2, '.', ''),
            $row->gst_percent !== null ? number_format((float) $row->gst_percent, 2, '.', '') : '0.00',
            number_format((float) ($row->gst_amount ?? 0), 2, '.', ''),
            number_format((float) $total, 2, '.', ''),
            $row->note ?? '-',
            $date ? $date->format('d M Y, h:i A') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
