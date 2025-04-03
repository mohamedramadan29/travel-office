<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Requests\FaqRequest;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\FaqService;

class FaqController extends Controller
{
    use Message_Trait;
    protected $faqService;
    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }
    public function index()
    {
        $faqs = $this->faqService->getFaqs();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        //
    }

    public function store(FaqRequest $request)
    {
        $data = $request->only('question', 'answer');
        $faq = $this->faqService->createFaq($data);
        if (!$faq) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'حدث خطأ ما أثناء إنشاء الكوبون'
                ],
                500
            );
        }
        return response()->json(
            [
                'status' => 'success',
                'message' => ' تم إضافة السؤال بنجاح ',
                'faq' => $faq
            ],
            200
        );
    }

    public function update(FaqRequest $request, string $id)
    {
        $data = $request->only('question', 'answer', 'id');
        $faq = $this->faqService->updateFaq($id, $data);
        if (!$faq) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => ' لم يتم تحديث السؤال '
                ],
                500
            );
        }
        return response()->json(
            [
                'status' => 'success',
                'message' => ' تم تحديث السؤال بنجاح '
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $faq = $this->faqService->deleteFaq($id);
        if (!$faq) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => ' لم يتم حذف السؤال '
                ],
                500
            );
        }
        return response()->json(
            [
                'status' => 'success',
                'message' => ' تم حذف السؤال بنجاح '
            ],
            200
        );
    }
}
