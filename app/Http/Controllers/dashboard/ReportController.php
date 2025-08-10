<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\admin\Expense;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\SaleInvoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function ExpensesReport()
    {
        // الحصول على نطاق التاريخ أو الشهر من الطلب
        $fromDate = request()->input('from_date');
        $toDate = request()->input('to_date');
        $month = request()->input('month'); // مثل '2024-08'

        // تهيئة المصفوفات لبيانات الرسم البياني
        $months = [];
        $totalExpenses = [];

        // بناء الاستعلام للمصروفات
        $query = Expense::orderBy('id', 'DESC')
            ->whereNotNull('created_at')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(price) as total, category_id')
            ->groupBy('month', 'category_id')
            ->orderBy('month', 'desc');

        // تطبيق فلترة المدة إذا تم تقديمها
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($month) {
            // تطبيق فلترة الشهر إذا تم تقديمها
            [$year, $mon] = explode('-', $month);
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $mon);
        }

        $expenses = $query->get()->toArray();

        // تجميع الأشهر دون تكرار
        foreach ($expenses as $expense) {
            $monthFormatted = Carbon::createFromFormat('Y-m', $expense['month'])->format('F Y');
            if (!in_array($monthFormatted, $months)) {
                $months[] = $monthFormatted;
                $totalExpenses[] = $expense['total'];
            }
        }

        // ملء الأشهر المفقودة (حتى 7) إذا لم يتم تحديد فلتر
        if (!$fromDate && !$toDate && !$month) {
            $currentMonth = Carbon::now()->startOfMonth();
            for ($i = 6; $i >= 0; $i--) {
                $monthFormatted = $currentMonth->copy()->subMonths($i)->format('F Y');
                if (!in_array($monthFormatted, $months)) {
                    $months[] = $monthFormatted;
                    $totalExpenses[] = 0;
                }
            }
        }

        // ترتيب الأشهر والإجماليات
        array_multisort($months, $totalExpenses);

        $monthlyExpenses = [
            'labels' => $months,
            'data' => $totalExpenses,
        ];

        // جلب جميع المصروفات للجدول مع تطبيق الفلتر
        $allexpensesQuery = Expense::orderBy('created_at', 'DESC')->with('category');
        if ($fromDate && $toDate) {
            $allexpensesQuery->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($month) {
            [$year, $mon] = explode('-', $month);
            $allexpensesQuery->whereYear('created_at', $year)
                             ->whereMonth('created_at', $mon);
        }
        $allexpenses = $allexpensesQuery->get();

        return view('admin.reports.expenses', compact('expenses', 'monthlyExpenses', 'allexpenses', 'fromDate', 'toDate', 'month'));
    }

    public function PurchesInvoicesReport(Request $request)
    {
        // الحصول على نطاق التاريخ أو الشهر من الطلب
        $fromDate = request()->input('from_date');
        $toDate = request()->input('to_date');
        $month = request()->input('month'); // مثل '2024-08'

        // تهيئة المصفوفات لبيانات الرسم البياني
        $months = [];
        $totalPurchesInvoices = [];

        // بناء الاستعلام للمصروفات
        $query = PurcheInvoice::orderBy('id', 'DESC')
            ->whereNotNull('created_at')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc');

        // تطبيق فلترة المدة إذا تم تقديمها
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($month) {
            // تطبيق فلترة الشهر إذا تم تقديمها
            [$year, $mon] = explode('-', $month);
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $mon);
        }

        $purchesInvoices = $query->get()->toArray();

        // تجميع الأشهر دون تكرار
        foreach ($purchesInvoices as $purchesInvoice) {
            $monthFormatted = Carbon::createFromFormat('Y-m', $purchesInvoice['month'])->format('F Y');
            if (!in_array($monthFormatted, $months)) {
                $months[] = $monthFormatted;
                $totalPurchesInvoices[] = $purchesInvoice['count'];
            }
        }

        // ملء الأشهر المفقودة (حتى 7) إذا لم يتم تحديد فلتر
        if (!$fromDate && !$toDate && !$month) {
            $currentMonth = Carbon::now()->startOfMonth();
            for ($i = 6; $i >= 0; $i--) {
                $monthFormatted = $currentMonth->copy()->subMonths($i)->format('F Y');
                if (!in_array($monthFormatted, $months)) {
                    $months[] = $monthFormatted;
                    $totalPurchesInvoices[] = 0;
                }
            }
        }

        // ترتيب الأشهر والإجماليات
        array_multisort($months, $totalPurchesInvoices);

        $monthlyPurchesInvoices = [
            'labels' => $months,
            'data' => $totalPurchesInvoices,
        ];

        // جلب جميع المصروفات للجدول مع تطبيق الفلتر
        $allPurchesInvoicesQuery = PurcheInvoice::orderBy('created_at', 'DESC')->with('supplier', 'category');
        if ($fromDate && $toDate) {
            $allPurchesInvoicesQuery->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($month) {
            [$year, $mon] = explode('-', $month);
            $allPurchesInvoicesQuery->whereYear('created_at', $year)
                             ->whereMonth('created_at', $mon);
        }
        $allPurchesInvoices = $allPurchesInvoicesQuery->get();

        return view('admin.reports.purches-invoice', compact('purchesInvoices', 'monthlyPurchesInvoices', 'allPurchesInvoices', 'fromDate', 'toDate', 'month'));
    }

    public function SalesInvoicesReport(Request $request)
    {
        // الحصول على نطاق التاريخ أو الشهر من الطلب
        $fromDate = request()->input('from_date');
        $toDate = request()->input('to_date');
        $month = request()->input('month'); // مثل '2024-08'

        // تهيئة المصفوفات لبيانات الرسم البياني
        $months = [];
        $totalSalesInvoices = [];

        // بناء الاستعلام للمصروفات
        $query = SaleInvoice::orderBy('id', 'DESC')
            ->whereNotNull('created_at')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc');

        // تطبيق فلترة المدة إذا تم تقديمها
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($month) {
            // تطبيق فلترة الشهر إذا تم تقديمها
            [$year, $mon] = explode('-', $month);
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $mon);
        }

        $salesInvoices = $query->get()->toArray();

        // تجميع الأشهر دون تكرار
        foreach ($salesInvoices as $salesInvoice) {
            $monthFormatted = Carbon::createFromFormat('Y-m', $salesInvoice['month'])->format('F Y');
            if (!in_array($monthFormatted, $months)) {
                $months[] = $monthFormatted;
                $totalSalesInvoices[] = $salesInvoice['count'];
            }
        }

        // ملء الأشهر المفقودة (حتى 7) إذا لم يتم تحديد فلتر
        if (!$fromDate && !$toDate && !$month) {
            $currentMonth = Carbon::now()->startOfMonth();
            for ($i = 6; $i >= 0; $i--) {
                $monthFormatted = $currentMonth->copy()->subMonths($i)->format('F Y');
                if (!in_array($monthFormatted, $months)) {
                    $months[] = $monthFormatted;
                    $totalSalesInvoices[] = 0;
                }
            }
        }

        // ترتيب الأشهر والإجماليات
        array_multisort($months, $totalSalesInvoices);

        $monthlySalesInvoices = [
            'labels' => $months,
            'data' => $totalSalesInvoices,
        ];

        // جلب جميع المصروفات للجدول مع تطبيق الفلتر
        $allSalesInvoicesQuery = SaleInvoice::orderBy('created_at', 'DESC')->with('supplier', 'category','client');
        if ($fromDate && $toDate) {
            $allSalesInvoicesQuery->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($month) {
            [$year, $mon] = explode('-', $month);
            $allSalesInvoicesQuery->whereYear('created_at', $year)
                             ->whereMonth('created_at', $mon);
        }
        $allSalesInvoices = $allSalesInvoicesQuery->get();

        return view('admin.reports.sales-invoices', compact('salesInvoices', 'monthlySalesInvoices', 'allSalesInvoices', 'fromDate', 'toDate', 'month'));
    }
}
