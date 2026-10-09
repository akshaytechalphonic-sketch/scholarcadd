<?php

namespace Modules\FAQS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\FAQS\Services\FAQSService;
use Illuminate\Http\Request;

class HiringCompaniesController extends Controller
{
    protected FAQSService $service;

    public function __construct(FAQSService $service)
    {
        $this->service = $service;
    }

    protected function FaqsIndex(Request $request)
    {
        return $this->service->Faqscourse_index($request);
    }
    protected function FaqsCourseCreate(Request $request)
    {
        return $this->service->FaqsCourseCreate($request);
    }
    protected function FaqsCourselStore(Request $request)
    {
        return $this->service->FaqsCourseStore($request);
    }
    protected function FaqsCourseEdit(Request $request)
    {
        return $this->service->faqsCourseCreate($request);
    }
    protected function FaqsCourseUpdate(Request $request)
    {
        return $this->service->FaqsCourseStore($request);
    }
    protected function FaqsCourseDestroy(Request $request)
    {
        return $this->service->FaqsCourseDestroy($request);
    }
     protected function FaqsCourseleView(Request $request)
    {
        return $this->service->faqsCourseCreate($request, 'view');
    }
    protected function get_stateName(Request $request)
    {
        $data = $this->service->getthedataofState($request->parent_id);
        return response()->json($data);
    }





}
