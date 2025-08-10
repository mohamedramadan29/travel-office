<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Models\admin\PurcheInvoice;
use App\Http\Controllers\Controller;
use App\Models\admin\PurcheInvoiceReturn;
use App\Exports\PurchesInvoicesReturnExport;

class PurchesInvoiceReturnController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $invoices = PurcheInvoiceReturn::orderBy('id','Desc')->paginate();
        return view('admin.invoices.purches-returns.index',compact('invoices'));
    }

    public function destroy(string $id)
    {
        $invoice = PurcheInvoiceReturn::findOrFail($id);
        $invoice->delete();
        return $this->success_message('تم حذف الفاتورة بنجاح');
    }


    ########################################### Generate All Purches Invoices  Pdf ##########################################
    public function PurchesInvoicesReturnPdf(){
        $invoices = PurcheInvoiceReturn::latest()->get();
        // إعداد محتوى HTML
        $html = '
        <html lang="ar" dir="rtl">
        <head>
            <style>
                body {
                    font-family: "Cairo", sans-serif; /* اختر خط يدعم اللغة العربية */
                    text-align: right; /* محاذاة النصوص لليمين */
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: right; /* لمحاذاة النصوص داخل الجدول */
                }
                th {
                    background-color: #f2f2f2; /* لون خلفية للرأس */
                }
            </style>
        </head>
        <body>
            <h1> فواتير الارجاع للشراء </h1>

            <table>
                <thead>
                    <tr>
                         <th> نوع الفاتورة </th>
                        <th> البيان </th>
                        <th> الرقم المرجعي </th>
                        <th> المورد </th>
                        <th> التصنيف </th>
                        <th> الكمية </th>
                        <th> السعر الكلي </th>
                        <th> السعر الارجاع  </th>
                        <th> تاريخ الانشاء </th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($invoices as $invoice) {

            $html .= '
                    <tr>
                        <td>' . 'فاتورة ارجاع' . '</td>
                        <td>' . $invoice->bayan_txt . '</td>
                        <td>' . $invoice->referance_number . '</td>
                        <td>' . $invoice->supplier->name . '</td>
                        <td>' . $invoice->category->name . '</td>
                        <td>' . $invoice->qyt . '</td>
                        <td>' . number_format($invoice->total_price, 2) . ' دينار</td>
                        <td>' . number_format($invoice->return_price, 2) . ' دينار</td>
                        <td>' . $invoice->created_at->format('Y-m-d H:i') . '</td>
                    </tr>';
        }
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        // إعداد mPDF
        $mpdf = new Mpdf([
            'default_font' => 'Cairo', // خط يدعم اللغة العربية
        ]);

        // تحميل المحتوى إلى ملف PDF
        $mpdf->WriteHTML($html);
        // توليد ملف PDF وإرساله للتنزيل
        return $mpdf->Output('تقرير عن فواتير الشراء.pdf', 'I'); // 'I' لعرض الملف في المتصفح

    }

    ######################################### Generate Purches Invoices Excel ############################

    public function PurchesInvoicesReturnExcel(){
        return (new PurchesInvoicesReturnExport())->download('PurchesInvoicesReturn.xlsx');
    }

}
