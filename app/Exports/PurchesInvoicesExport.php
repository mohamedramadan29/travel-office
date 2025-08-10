<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\admin\PurcheInvoice;

class PurchesInvoicesExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    public function collection()
    {
        $invoices = PurcheInvoice::latest()->get();
        return $invoices->map(function ($invoice) {
            return [
                $invoice->type,
                $invoice->bayan_txt,
                $invoice->referance_number,
                $invoice->supplier->name,
                $invoice->category->name ?? 'غير محدد',
                $invoice->qyt,
                number_format($invoice->total_price,2) .' دينار',
                $invoice->created_at->format('Y-m-d H:i'),
            ];
        });
    }
    public function headings(): array
    {
        return [
            'نوع الفاتورة',
            'البيان',
            'الرقم المرجعي',
            'المورد',
            'التصنيف',
            'الكمية',
            'السعر الكلي',
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
