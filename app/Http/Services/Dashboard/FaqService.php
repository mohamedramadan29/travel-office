<?php

namespace App\Http\Services\Dashboard;

use App\Http\Repositories\Dashboard\FaqRepository;

class FaqService
{
    protected $faqRepository;

    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function getFaq($id)
    {
        $faq=  $this->faqRepository->getFaq($id);
        if (!$faq) {
            return response()->json(['message' => 'Faq not found'], 404);
        }
        return $faq;
    }

    public function getFaqs()
    {
        return $this->faqRepository->getFaqs();
    }

    public function createFaq($data)
    {
        return $this->faqRepository->createFaq($data);
    }
    public function updateFaq($id, $data)
    {
        $faq = $this->getFaq($id);
        if (!$faq) {
            return response()->json(['message' => 'Faq not found'], 404);
        }
        return $this->faqRepository->updateFaq($data, $faq);
    }
    public function deleteFaq($id)
    {
        $faq = $this->getFaq($id);
        if (!$faq) {
            return response()->json(['message' => 'Faq not found'], 404);
        }
        return $this->faqRepository->deleteFaq($faq);
    }

}