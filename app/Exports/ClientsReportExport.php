<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\admin\Client;

class ClientsReportExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    protected $client_ids;

    public function __construct($client_ids = null)
    {
        $this->client_ids = $client_ids;
    }

    public function collection()
    {
        $query = Client::latest();

        if ($this->client_ids && count($this->client_ids) > 0) {
            $query->whereIn('id', $this->client_ids);
        }

        $clients = $query->get();

        return $clients->map(function ($client) {
            $balance = $client->balance();
            $status = '';
            if ($balance > 0) {
                $status = 'مدين';
            } elseif ($balance < 0) {
                $status = 'دائن';
            }

            return [
                $client->name,
                number_format($balance, 2),
                $status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'الرصيد',
            'دائن / مدين',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // ضبط التنسيق لدعم اللغة العربية
        $sheet->getStyle('A:Z')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT, // محاذاة إلى اليمين
                'vertical' => Alignment::VERTICAL_CENTER,    // محاذاة عمودية
            ],
            'font' => [
                'name' => 'Arial', // استخدام خط يدعم العربية
                'size' => 12,
            ],
        ]);

        // تعيين اتجاه الورقة إلى RTL
        $sheet->setRightToLeft(true);

        return [];
    }
}
