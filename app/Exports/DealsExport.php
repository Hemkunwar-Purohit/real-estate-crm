<?php

namespace App\Exports;

use App\Models\Deal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DealsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Deal::with(['client', 'property', 'assignedAgent'])->latest();

        if (! empty($this->filters['stage'])) {
            $query->where('stage', $this->filters['stage']);
        }

        if (! empty($this->filters['from_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if (! empty($this->filters['to_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Deal Title',
            'Client',
            'Property',
            'Value (INR)',
            'Commission',
            'Stage',
            'Agent',
            'Expected Close',
            'Actual Close',
            'Date Added',
        ];
    }

    public function map($deal): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $deal->title,
            $deal->client->name,
            $deal->property ? $deal->property->title : '—',
            number_format($deal->deal_value),
            $deal->commission ? number_format($deal->commission) : '—',
            config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage,
            $deal->assignedAgent->name,
            $deal->expected_close_date ? $deal->expected_close_date->format('d M Y') : '—',
            $deal->actual_close_date ? $deal->actual_close_date->format('d M Y') : '—',
            $deal->created_at->format('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '28A745']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
