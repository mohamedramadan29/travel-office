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
use App\Livewire\Dashboard\PurchesInvoiceReturn;
use App\Models\admin\EmployeeSalary;
use App\Models\admin\ExpenceCategory;
use App\Models\admin\PurcheInvoiceReturn;
use App\Models\admin\SaleInvoiceReturn;

class IncomeReportController extends Controller
{
    public function IncomeReport()
    {
        // استرجاع البيانات
        $expenses = Expense::all();
        $mothlySalary = MothlySalary::all();
        $purchesInvoices = PurcheInvoice::all();
        $purchesInvoicesReturn = PurcheInvoiceReturn::all();
        $saleInvoices = SaleInvoice::all();
        $saleInvoicesReturn = SaleInvoiceReturn::all();
        $employeeSalaries = EmployeeSalary::all();

        // حساب الإجماليات
        $expensesTotal = $expenses->sum('price');
        $purchesInvoicesTotal = $purchesInvoices->sum('total_price');
        $purchesInvoicesTotalNotReturned = $purchesInvoices->where('return_status', 'not_returned')->sum('total_price');
        $purchesInvoicesTotalReturn = $purchesInvoicesReturn->sum('total_price');
        $purchesInvoicesTotalReturnMinusProfit = $purchesInvoicesReturn->sum('additional_profit'); // خسارة إضافية للمورد
        $saleInvoicesTotal = $saleInvoices->sum('total_price');
        $saleInvoicesTotalReturn = $saleInvoicesReturn->sum('total_price');
        $saleInvoicesTotalNotReturned = $saleInvoices->where('return_status', 'not_returned')->sum('total_price');
        $employeeSalariesTotal = $employeeSalaries->sum('salary');

        // حساب صافي المشتريات
        $totalLastPurches = $purchesInvoicesTotalNotReturned - $purchesInvoicesTotalReturn;
        $totalLastPurches += $purchesInvoicesTotalReturnMinusProfit; // إضافة الخسارة الإضافية للمورد

        // حساب صافي المبيعات
        $totalLastSales = $saleInvoicesTotalNotReturned - $saleInvoicesTotalReturn;
        $totalLastSalesReturnIncome = $saleInvoicesReturn->sum('additional_profit'); // الربح الإضافي من مرتجعات البيع

        // حساب صافي الدخل
        $totalIncome = ($totalLastSales + $totalLastSalesReturnIncome) - ($expensesTotal + $totalLastPurches + $employeeSalariesTotal);

        // التقرير الشهري
        $monthlyReport = DB::select("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS month,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS expenses_total,
                COALESCE(SUM(CASE WHEN type = 'purchase' AND return_status = 'not_returned' THEN amount ELSE 0 END), 0) AS purchases_total,
                COALESCE(SUM(CASE WHEN type = 'purchase_return' THEN amount ELSE 0 END), 0) AS purchases_return_total,
                COALESCE(SUM(CASE WHEN type = 'purchase_return_profit' THEN amount ELSE 0 END), 0) AS purchases_return_profit_total,
                COALESCE(SUM(CASE WHEN type = 'sale' AND return_status = 'not_returned' THEN amount ELSE 0 END), 0) AS sales_total,
                COALESCE(SUM(CASE WHEN type = 'sale_return' THEN amount ELSE 0 END), 0) AS sales_return_total,
                COALESCE(SUM(CASE WHEN type = 'sale_return_profit' THEN amount ELSE 0 END), 0) AS sales_return_profit_total,
                COALESCE(SUM(CASE WHEN type = 'salary' THEN amount ELSE 0 END), 0) AS salary_total
            FROM (
                SELECT created_at, price AS amount, 'expense' AS type, NULL AS return_status
                FROM expenses
                UNION ALL
                SELECT created_at, total_price AS amount, 'purchase' AS type, return_status
                FROM purche_invoices
                UNION ALL
                SELECT created_at, total_price AS amount, 'purchase_return' AS type, NULL AS return_status
                FROM purche_invoice_returns
                UNION ALL
                SELECT created_at, additional_profit AS amount, 'purchase_return_profit' AS type, NULL AS return_status
                FROM purche_invoice_returns
                UNION ALL
                SELECT created_at, total_price AS amount, 'sale' AS type, return_status
                FROM sale_invoices
                UNION ALL
                SELECT created_at, total_price AS amount, 'sale_return' AS type, NULL AS return_status
                FROM sale_invoice_returns
                UNION ALL
                SELECT created_at, additional_profit AS amount, 'sale_return_profit' AS type, NULL AS return_status
                FROM sale_invoice_returns
                UNION ALL
                SELECT created_at, salary AS amount, 'salary' AS type, NULL AS return_status
                FROM employee_salaries
            ) AS combined
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ");

        // حساب إجمالي الدخل لكل شهر مع مراعاة الخسارة الإضافية للمورد
        $monthlyReport = array_map(function ($item) {
            // صافي المشتريات الشهرية = (إجمالي المشتريات غير المرتجعة - إجمالي مرتجعات الشراء) + الخسارة الإضافية للمورد
            $netPurchases = ($item->purchases_total - $item->purchases_return_total) + $item->purchases_return_profit_total;
            // صافي الدخل = (صافي المبيعات + الربح الإضافي من مرتجعات البيع) - (المصروفات + صافي المشتريات + الرواتب)
            $item->income = ($item->sales_total - $item->sales_return_total + $item->sales_return_profit_total) - ($item->expenses_total + $netPurchases + $item->salary_total);
            return $item;
        }, $monthlyReport);

        // للتحقق من البيانات
        // dd($totalIncome, $monthlyReport);

        return view('admin.income-report.index', compact(
            'expenses', 'purchesInvoices', 'purchesInvoicesReturn',
            'saleInvoices', 'saleInvoicesReturn',
            'expensesTotal', 'purchesInvoicesTotal',
            'purchesInvoicesTotalReturn', 'saleInvoicesTotal',
            'saleInvoicesTotalReturn', 'totalIncome',
            'employeeSalaries', 'monthlyReport',
            'employeeSalariesTotal', 'totalLastSalesReturnIncome',
            'purchesInvoicesTotalNotReturned', 'saleInvoicesTotalNotReturned'
        ));
    }

    #########


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

    $purchesInvoicesReturn = PurcheInvoiceReturn::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    $saleInvoices = SaleInvoice::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    $saleInvoicesReturn = SaleInvoiceReturn::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    $employeeSalaries = EmployeeSalary::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNum)
        ->get();

    // حساب الإجماليات
    $expensesTotal = $expenses->sum('price');
    $purchesInvoicesTotal = $purchesInvoices->sum('total_price');
    $purchesInvoicesTotalNotReturned = $purchesInvoices->where('return_status', 'not_returned')->sum('total_price');
    $purchesInvoicesTotalReturn = $purchesInvoicesReturn->sum('total_price');
    $purchesInvoicesTotalReturnMinusProfit = $purchesInvoicesReturn->sum('additional_profit'); // خسارة إضافية للمورد
    $saleInvoicesTotal = $saleInvoices->sum('total_price');
    $saleInvoicesTotalNotReturned = $saleInvoices->where('return_status', 'not_returned')->sum('total_price');
    $saleInvoicesTotalReturn = $saleInvoicesReturn->sum('total_price');
    $employeeSalariesTotal = $employeeSalaries->sum('salary');

    // حساب صافي المشتريات
    $totalLastPurches = $purchesInvoicesTotalNotReturned - $purchesInvoicesTotalReturn;
    $totalLastPurches += $purchesInvoicesTotalReturnMinusProfit; // إضافة الخسارة الإضافية للمورد

    // حساب صافي المبيعات
    $totalLastSales = $saleInvoicesTotalNotReturned - $saleInvoicesTotalReturn;
    $totalLastSalesReturnIncome = $saleInvoicesReturn->sum('additional_profit'); // الربح الإضافي من مرتجعات البيع

    // حساب صافي الدخل
    $totalIncome = ($totalLastSales + $totalLastSalesReturnIncome) - ($expensesTotal + $totalLastPurches + $employeeSalariesTotal);

    // التقرير الشهري المفصل
    $monthlyReport = DB::select("
        SELECT
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS expenses_total,
            COALESCE(SUM(CASE WHEN type = 'purchase' AND return_status = 'not_returned' THEN amount ELSE 0 END), 0) AS purchases_total,
            COALESCE(SUM(CASE WHEN type = 'purchase_return' THEN amount ELSE 0 END), 0) AS purchases_return_total,
            COALESCE(SUM(CASE WHEN type = 'purchase_return_profit' THEN amount ELSE 0 END), 0) AS purchases_return_profit_total,
            COALESCE(SUM(CASE WHEN type = 'sale' AND return_status = 'not_returned' THEN amount ELSE 0 END), 0) AS sales_total,
            COALESCE(SUM(CASE WHEN type = 'sale_return' THEN amount ELSE 0 END), 0) AS sales_return_total,
            COALESCE(SUM(CASE WHEN type = 'sale_return_profit' THEN amount ELSE 0 END), 0) AS sales_return_profit_total,
            COALESCE(SUM(CASE WHEN type = 'salary' THEN amount ELSE 0 END), 0) AS salary_total
        FROM (
            SELECT created_at, price AS amount, 'expense' AS type, NULL AS return_status
            FROM expenses
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_price AS amount, 'purchase' AS type, return_status
            FROM purche_invoices
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_price AS amount, 'purchase_return' AS type, NULL AS return_status
            FROM purche_invoice_returns
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, additional_profit AS amount, 'purchase_return_profit' AS type, NULL AS return_status
            FROM purche_invoice_returns
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_price AS amount, 'sale' AS type, return_status
            FROM sale_invoices
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, total_price AS amount, 'sale_return' AS type, NULL AS return_status
            FROM sale_invoice_returns
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, additional_profit AS amount, 'sale_return_profit' AS type, NULL AS return_status
            FROM sale_invoice_returns
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            UNION ALL
            SELECT created_at, salary AS amount, 'salary' AS type, NULL AS return_status
            FROM employee_salaries
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
        ) AS combined
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ", [
        $year, $monthNum, // expenses
        $year, $monthNum, // purche_invoices
        $year, $monthNum, // purche_invoice_returns
        $year, $monthNum, // purche_invoice_returns (additional_profit)
        $year, $monthNum, // sale_invoices
        $year, $monthNum, // sale_invoice_returns
        $year, $monthNum, // sale_invoice_returns (additional_profit)
        $year, $monthNum  // employee_salaries
    ]);

    // حساب إجمالي الدخل للشهر مع مراعاة الخسارة الإضافية للمورد
    $monthlyReport = array_map(function ($item) {
        // صافي المشتريات الشهرية = (إجمالي المشتريات غير المرتجعة - إجمالي مرتجعات الشراء) + الخسارة الإضافية للمورد
        $netPurchases = ($item->purchases_total - $item->purchases_return_total) + $item->purchases_return_profit_total;
        // صافي الدخل = (صافي المبيعات + الربح الإضافي من مرتجعات البيع) - (المصروفات + صافي المشتريات + الرواتب)
        $item->income = ($item->sales_total - $item->sales_return_total + $item->sales_return_profit_total) - ($item->expenses_total + $netPurchases + $item->salary_total);
        return $item;
    }, $monthlyReport);

    // جلب الفئات النشطة فقط
    $categories = Category::active()->get();

    // جلب فواتير المبيعات حسب الفئات
    $salesInvoicesByCategory = $categories->map(function ($category) use ($year, $monthNum) {
        $invoices = $category->saleInvoices()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->where('return_status', 'not_returned')
            ->get();
        return [
            'category' => $category,
            'invoices' => $invoices,
            'total' => $invoices->sum('total_price'),
        ];
    });

    // جلب فواتير المشتريات حسب الفئات
    $purchasesInvoicesByCategory = $categories->map(function ($category) use ($year, $monthNum) {
        $invoices = PurcheInvoice::where('category_id', $category->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->where('return_status', 'not_returned')
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

    // للتحقق من البيانات
    // dd($totalIncome, $monthlyReport);

    // إرجاع العرض مع البيانات
    return view('admin.income-report.monthly-report', compact(
        'expenses', 'purchesInvoices', 'purchesInvoicesReturn',
        'saleInvoices', 'saleInvoicesReturn',
        'expensesTotal', 'purchesInvoicesTotal', 'purchesInvoicesTotalReturn',
        'saleInvoicesTotal', 'saleInvoicesTotalReturn', 'totalIncome',
        'monthlyReport', 'month', 'categories', 'salesInvoicesByCategory',
        'purchasesInvoicesByCategory', 'expensesByCategory',
        'employeeSalaries', 'employeeSalariesTotal',
        'purchesInvoicesTotalNotReturned', 'saleInvoicesTotalNotReturned',
        'totalLastSalesReturnIncome', 'purchesInvoicesTotalReturnMinusProfit'
    ));
}


}
