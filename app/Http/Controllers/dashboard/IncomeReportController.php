<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Models\admin\Expense;
use App\Models\admin\Category;
use App\Models\admin\SaleInvoice;
use App\Models\admin\MothlySalary;
use Illuminate\Support\Facades\DB;
use App\Models\admin\PurcheInvoice;
use App\Http\Controllers\Controller;
use App\Models\admin\ExpenceCategory;

class IncomeReportController extends Controller
{
    public function IncomeReport()
    {
        // التقرير الكلي (كما في الكود الأصلي)
        $expenses = Expense::all();
        $mothlySalary = MothlySalary::all();
        $purchesInvoices = PurcheInvoice::all();
        $saleInvoices = SaleInvoice::all();
        $expensesTotal = $expenses->sum('price');
        $purchesInvoicesTotal = $purchesInvoices->sum('total_price');
        $saleInvoicesTotal = $saleInvoices->sum('total_price');
        $mothlySalaryTotal = $mothlySalary->sum('total_salary');
        $totalIncome = $saleInvoicesTotal - ($expensesTotal + $purchesInvoicesTotal+$mothlySalaryTotal);


        // التقرير الشهري
        $monthlyReport = DB::select("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS month,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS expenses_total,
                COALESCE(SUM(CASE WHEN type = 'purchase' THEN amount ELSE 0 END), 0) AS purchases_total,
                COALESCE(SUM(CASE WHEN type = 'sale' THEN amount ELSE 0 END), 0) AS sales_total,
                COALESCE(SUM(CASE WHEN type = 'salary' THEN amount ELSE 0 END), 0) AS salary_total
            FROM (
                SELECT created_at, price AS amount, 'expense' AS type
                FROM expenses
                UNION ALL
                SELECT created_at, total_price AS amount, 'purchase' AS type
                FROM purche_invoices
                UNION ALL
                SELECT created_at, total_price AS amount, 'sale' AS type
                FROM sale_invoices
                UNION ALL
                SELECT created_at, total_salary AS amount, 'salary' AS type
                FROM mothly_salaries
            ) AS combined
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ");

        // حساب إجمالي الدخل لكل شهر
        $monthlyReport = array_map(function ($item) {
            $item->income = $item->sales_total - ($item->expenses_total + $item->purchases_total+$item->salary_total);
            return $item;
        }, $monthlyReport);

        return view('admin.income-report.index', compact(
            'expenses', 'purchesInvoices', 'saleInvoices',
            'expensesTotal', 'purchesInvoicesTotal', 'saleInvoicesTotal', 'totalIncome',
            'mothlySalary',
            'monthlyReport',
            'mothlySalaryTotal'
        ));
    }


    #############################################

    public function IncomeReportMonthly($month)
{
    // التحقق من صيغة الشهر (YYYY-MM)
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
        abort(404, 'صيغة الشهر غير صحيحة');
    }

    // استخراج السنة والشهر من المدخل
    $year = substr($month, 0, 4);
    $monthNum = substr($month, 5, 2);

    // جلب البيانات المصفاة حسب الشهر والسنة
    $expenses = Expense::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    $purchesInvoices = PurcheInvoice::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    $saleInvoices = SaleInvoice::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();
    $mothlySalary = MothlySalary::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    // حساب الإجماليات
    $expensesTotal = $expenses->sum('price');
    $purchesInvoicesTotal = $purchesInvoices->sum('total_price');
    $saleInvoicesTotal = $saleInvoices->sum('total_price');
    $mothlySalaryTotal = $mothlySalary->sum('total_salary');
    $totalIncome = $saleInvoicesTotal - ($expensesTotal + $purchesInvoicesTotal+$mothlySalaryTotal);

    // التقرير الشهري المفصل
    $monthlyReport = DB::select("
        SELECT
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS expenses_total,
            COALESCE(SUM(CASE WHEN type = 'purchase' THEN amount ELSE 0 END), 0) AS purchases_total,
            COALESCE(SUM(CASE WHEN type = 'sale' THEN amount ELSE 0 END), 0) AS sales_total,
            COALESCE(SUM(CASE WHEN type = 'salary' THEN amount ELSE 0 END), 0) AS salary_total
        FROM (
            SELECT created_at, price AS amount, 'expense' AS type
            FROM expenses
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_price AS amount, 'purchase' AS type
            FROM purche_invoices
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_price AS amount, 'sale' AS type
            FROM sale_invoices
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_salary AS amount, 'salary' AS type
            FROM mothly_salaries
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
        ) AS combined
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ", [$year, $monthNum, $year, $monthNum, $year, $monthNum, $year, $monthNum]);

    // حساب إجمالي الدخل للشهر
    $monthlyReport = array_map(function ($item) {
        $item->income = $item->sales_total - ($item->expenses_total + $item->purchases_total+$item->salary_total);
        return $item;
    }, $monthlyReport);

    // جلب الفئات النشطة فقط
    $categories = Category::active()->get();

    // جلب فواتير المبيعات حسب الفئات
    $salesInvoicesByCategory = $categories->map(function ($category) use ($year, $monthNum) {
        $invoices = $category->saleInvoices()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->get();
        return [
            'category' => $category,
            'invoices' => $invoices,
            'total' => $invoices->sum('total_price'),
        ];
    });

    // جلب فواتير المشتريات حسب الفئات (بافتراض وجود علاقة مع Category)
    $purchasesInvoicesByCategory = $categories->map(function ($category) use ($year, $monthNum) {
        $invoices = PurcheInvoice::where('category_id', $category->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->get();
        return [
            'category' => $category,
            'invoices' => $invoices,
            'total' => $invoices->sum('total_price'),
        ];
    });

   // جلب فئات المصروفات النشطة
   $expensesCategories = ExpenceCategory::active()->get();
   $expensesByCategory = $expensesCategories->map(function ($category) use ($year, $monthNum) {
       $expenses = $category->expense()
           ->whereYear('created_at', $year)
           ->whereMonth('created_at', $monthNum)
           ->get();
       return [
           'category' => $category,
           'expenses' => $expenses,
           'total' => $expenses->sum('price'),
       ];
   });
    // إرجاع العرض مع البيانات
    return view('admin.income-report.monthly-report', compact(
        'expenses', 'purchesInvoices', 'saleInvoices',
        'expensesTotal', 'purchesInvoicesTotal', 'saleInvoicesTotal', 'totalIncome',
        'monthlyReport', 'month', 'categories', 'salesInvoicesByCategory', 'purchasesInvoicesByCategory', 'expensesByCategory', 'mothlySalary', 'mothlySalaryTotal'
    ));
}


}
