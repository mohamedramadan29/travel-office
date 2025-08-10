<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\admin\Expense;

class ExpencesExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    public function collection()
    {
        $expenses = Expense::latest()->get();
        return $expenses->map(function ($expense) {
            return [
                $expense->category->name,
                number_format($expense->price, 2) . ' دينار',
                $expense->safe->name,
                $expense->created_at,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'التصنيف',
            'المبلغ',
            'الخزينة',
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
