<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\DynamicCourseTuitionFeeService;

class DynamicCourseTuitionFeeController extends Controller
{
    protected DynamicCourseTuitionFeeService $service;

    public function __construct(DynamicCourseTuitionFeeService $service)
    {
        $this->service = $service;
    }

    protected function tuitionFeeList(Request $request)
    {
        return $this->service->tuitionFeeList($request);

    }
    protected function tuitionFeeCreate($id = null)
    {
        return $this->service->tuitionFeeCreate($id);
    }
    public function tuitionFeeStore(Request $request)
    {
        $result = $this->service->tuitionFeeStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('tuition-fee-list')->with('success', $result['message']);
    }
    protected function tuitionFeeEdit(Request $request)
    {
        return $this->service->tuitionFeeCreate($request->id);
    }
    protected function tuitionFeeUpdate(Request $request)
    {
       $result = $this->service->tuitionFeeStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('tuition-fee-list')->with('success', $result['message']);
    }

}
