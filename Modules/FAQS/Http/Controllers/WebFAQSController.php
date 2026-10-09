<?php

namespace Modules\FAQS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\FAQS\Services\WebFAQSService;
use Illuminate\Http\Request;

class WebFAQSController extends Controller
{
    protected WebFAQSService $service;

    public function __construct(WebFAQSService $service)
    {
        $this->service = $service;
    }

    protected function FaqsIndexList(Request $request)
    {
        return $this->service->Faqscourselist_index($request);
    }
    protected function FaqsGeneralCreate(Request $request)
    {
        return $this->service->faqsGeneralCreate($request);
    }
    protected function FaqsGeneralStore(Request $request)
    {
        return $this->service->FaqsGeneralStore($request);
    }
    protected function FaqsGeneralEdit(Request $request)
    {
        return $this->service->faqsGeneralCreate($request);
    }
    protected function FaqsGeneralUpdate(Request $request)
    {
        return $this->service->FaqsGeneralStore($request);
    }

    protected function FaqsGeneralDestroy(Request $request)
    {
        return $this->service->FaqsGeneralDestroy($request);
    }
    protected function FaqsGeneralleView(Request $request)
    {
        return $this->service->faqsGeneralCreate($request, 'view');
    }


    // FAQS Category getting Methods

    protected function FaqsCategoryIndexList(Request $request)
    {
        return $this->service->FaqsCategorylist_index($request);
    }
    protected function FaqsCategoryListCreate(Request $request)
    {
        return $this->service->FaqsCategory_create($request);
    }
    protected function FaqsCategoryStore(Request $request)
    {
        return $this->service->FaqsCategoryStore($request);
    }

    protected function FaqsCategoryView(Request $request)
    {
        return $this->service->FaqsCategory_create($request->id, 'view');
    }
    protected function FaqsCategoryEdit(Request $request)
    {
        return $this->service->FaqsCategory_create($request);
    }
    protected function FaqsCategoryUpdate(Request $request)
    {
        return $this->service->FaqsCategoryStore($request);
    }
    protected function FaqsCetegoryDestroy(Request $request)
    {
        return $this->service->FaqsCetegoryDestroy($request);
    }
}
