<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\CourseSeoService;

class CourseSeoController extends Controller
{
    protected CourseSeoService $service;

    public function __construct(CourseSeoService $service)
    {
        $this->service = $service;
    }

    protected function CourseSeoList(Request $request)
    {
        return $this->service->CourseSeoList($request);

    }

     protected function CourseSeoCreate($id = null)
    {
        return $this->service->CourseSeoCreate($id);
    }

    public function CourseSeoStore(Request $request)
    {
        $result = $this->service->CourseSeoStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-seo-list')->with('success', $result['message']);
    }

    protected function CourseSeoEdit(Request $request)
    {
        return $this->service->CourseSeoCreate($request->id);
    }

    protected function CourseSeoUpdate(Request $request)
    {
       $result = $this->service->CourseSeoStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-seo-list')->with('success', $result['message']);
    }

    // public function getStates(Request $request)
    // {
    //     $countryId = $request->country_id;
    //     $states = $this->service->getStatesByCountry($countryId);
    //     return response()->json($states);
    // }


}
