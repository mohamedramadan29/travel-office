<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Models\admin\Expense;
use App\Models\admin\SaleInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\admin\PurcheInvoice;
use App\Http\Controllers\Controller;

class IncomeReportController extends Controller
{
    public function IncomeReport()
    {
        // التقرير الكلي (كما في الكود الأصلي)
        $expenses = Expense::all();
        $purchesInvoices = PurcheInvoice::all();
        $saleInvoices = SaleInvoice::all();
        $expensesTotal = $expenses->sum('price');
        $purchesInvoicesTotal = $purchesInvoices->sum('total_price');
        $saleInvoicesTotal = $saleInvoices->sum('total_price');
        $totalIncome = $saleInvoicesTotal - ($expensesTotal + $purchesInvoicesTotal);

        // التقرير الشهري
        $monthlyReport = DB::select("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS month,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS expenses_total,
                COALESCE(SUM(CASE WHEN type = 'purchase' THEN amount ELSE 0 END), 0) AS purchases_total,
                COALESCE(SUM(CASE WHEN type = 'sale' THEN amount ELSE 0 END), 0) AS sales_total
            FROM (
                SELECT created_at, price AS amount, 'expense' AS type
                FROM expenses
                UNION ALL
                SELECT created_at, total_price AS amount, 'purchase' AS type
                FROM purche_invoices
                UNION ALL
                SELECT created_at, total_price AS amount, 'sale' AS type
                FROM sale_invoices
            ) AS combined
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ");

        // حساب إجمالي الدخل لكل شهر
        $monthlyReport = array_map(function ($item) {
            $item->income = $item->sales_total - ($item->expenses_total + $item->purchases_total);
            return $item;
        }, $monthlyReport);

        return view('admin.income-report.index', compact(
            'expenses', 'purchesInvoices', 'saleInvoices',
            'expensesTotal', 'purchesInvoicesTotal', 'saleInvoicesTotal', 'totalIncome',
            'monthlyReport'
        ));
    }

}
