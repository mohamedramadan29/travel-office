<?php

namespace App\Exports;

use App\Models\admin\Supplier;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuppliersReportExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    protected $supplier_ids;

    public function __construct($supplier_ids = null)
    {
        $this->supplier_ids = $supplier_ids;
    }

    public function collection()
    {
        $query = Supplier::latest();
        if ($this->supplier_ids && count($this->supplier_ids) > 0) {
            $query->whereIn('id', $this->supplier_ids);
        }
        $suppliers = $query->get();

        return $suppliers->map(function ($supplier) {
            return [
                $supplier->name,
                $supplier->email,
                $supplier->mobile,
                $supplier->telegram,
                $supplier->whatsapp,
                number_format($supplier->balance(), 2),
                ($supplier->balance() > 0 ? 'دائن' : ($supplier->balance() < 0 ? 'مدين' : '')),
                $supplier->status,
                $supplier->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'اسم المورد',
            'البريد الالكتروني',
            'رقم الهاتف',
            'رقم التيلغرام',
            'رقم الواتساب',
            'الرصيد',
            'دائن / مدين',
            'الحالة',
            'تاريخ الاضافة',
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
