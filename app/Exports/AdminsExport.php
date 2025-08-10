<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\admin\Admin;

class AdminsExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    public function collection()
    {
        $admins = Admin::latest()->get();
        return $admins->map(function ($admin) {
            return [
                $admin->name,
                $admin->email,
                $admin->role->role,
                $admin->status,
                $admin->created_at->format('Y-m-d'),
            ];
        });
    }
    public function headings(): array
    {
        return [
            'اسم الموظف',
            'البريد الالكتروني',
            'الصلاحيات',
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
