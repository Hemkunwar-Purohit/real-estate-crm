<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Lead::with('assignedAgent')->latest();

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['source'])) {
            $query->where('source', $this->filters['source']);
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
            'Name',
            'Phone',
            'Email',
            'Source',
            'Status',
            'Looking To',
            'Property Type',
            'Budget Min',
            'Budget Max',
            'Preferred City',
            'Assigned Agent',
            'Date Added',
        ];
    }

    public function map($lead): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $lead->name,
            $lead->phone,
            $lead->email ?? '—',
            config('crm.lead_sources')[$lead->source] ?? $lead->source ?? '—',
            ucfirst($lead->status),
            ucfirst($lead->listing_type),
            config('crm.property_types')[$lead->property_type] ?? '—',
            $lead->budget_min ? number_format($lead->budget_min) : '—',
            $lead->budget_max ? number_format($lead->budget_max) : '—',
            $lead->preferred_city ?? '—',
            $lead->assignedAgent->name ?? '—',
            $lead->created_at->format('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
