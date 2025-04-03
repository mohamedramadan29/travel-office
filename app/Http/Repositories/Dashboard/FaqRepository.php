<?php

namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Faq;

class FaqRepository
{
    public function getFaq($id)
    {
        $faq = Faq::find($id);
        return $faq;
    }

    public function getFaqs()
    {
        $faqs = Faq::latest()->get();
        return $faqs;
    }

    public function createFaq($data)
    {
        $faq = Faq::create($data);
        return $faq;
    }
    public function updateFaq($data, $faq)
    {
        $faq->update($data);
        return $faq;
    }
    public function deleteFaq($faq)
    {
        $faq->delete();
        return $faq;
    }
}
