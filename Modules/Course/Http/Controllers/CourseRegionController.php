<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\CourseRegionService;

class CourseRegionController extends Controller
{
    protected CourseRegionService $service;

    public function __construct(CourseRegionService $service)
    {
        $this->service = $service;
    }

    protected function RegionList(Request $request)
    {
        return $this->service->RegionList($request);

    }
     protected function RegionCreate($id = null)
    {
        return $this->service->RegionCreate($id);
    }

    public function RegionStore(Request $request)
    {
        $result = $this->service->RegionStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('region-list')->with('success', $result['message']);
    }

        protected function RegionEdit(Request $request)
    {
        return $this->service->RegionCreate($request->id);
    }

    protected function RegionUpdate(Request $request)
    {
       $result = $this->service->RegionStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('region-list')->with('success', $result['message']);
    }
}
