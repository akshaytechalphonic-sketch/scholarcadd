<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Course\Services\CourseScheduleService;
use Illuminate\Http\Request;
use Modules\Course\Http\Requests\CourseRequest;
use Modules\Course\Http\Requests\BatchRequest;
use Illuminate\Support\Facades\DB;

class CourseScheduleController extends Controller
{
    protected CourseScheduleService $service;

    public function __construct(CourseScheduleService $service)
    {
        $this->service = $service;
    }
    //  ==================== Course Schedule ===========================

    protected function courseScheduleList(Request $request)
    {
        return $this->service->CourseScheduleList($request);
    }
    protected function courseScheduleCreate($id = null)
    {
        return $this->service->CourseScheduleCreate($id);
    }
    protected function courseScheduleStore(Request $request)
     {
        $result = $this->service->courseScheduleStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-schedule-list')->with('success', $result['message']);
    }
    protected function courseScheduleEdit(Request $request)
    {
        return $this->service->CourseScheduleCreate($request->id);
    }
    protected function courseScheduleUpdate(Request $request)
     {
        $result = $this->service->courseScheduleStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-schedule-list')->with('success', $result['message']);
    }
    protected function courseScheduleView(Request $request)
    {
        return $this->service->courseScheduleCreate($request->id, 'view');
    }
    protected function CourseScheduleDestroy(Request $request)
    {
        $result = $this->service->CourseScheduleDestroy($request->id);

            if (request()->ajax()) {
            return response()->json($result);
        }
        if (!$result['status']) {  // ✅ Gets array['status']
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-schedule-list')->with('success', $result['message']);  // ✅ Works!
    }

    protected function CourseScheduleBulkDestroy(Request $request)
    {
        return $this->service->CourseScheduleBulkDestroy($request);
    }


    public function getPlansByCourse($course_id)
    {
        $plans = DB::table('tbl_plans')
            ->leftJoin('tbl_plan_mappings', 'tbl_plans.id', '=', 'tbl_plan_mappings.plan_id')
            ->select('tbl_plans.*', 'tbl_plan_mappings.*')
            ->where('tbl_plan_mappings.course_id', $course_id)
            ->get();

        return response()->json($plans);
    }


    // ===================== course ======================
    protected function CourseView(Request $request)
    {
        return $this->service->CourseCreate($request->id, 'view');
    }
    protected function CourseEdit(Request $request)
    {
        return $this->service->CourseCreate($request->id);
    }
    protected function CourseUpdate(CourseRequest $request)
    {
        return $this->service->CourseStore($request);
    }
    protected function CourseDestroy(Request $request)
    {
        return $this->service->CourseDestroy($request->id);
    }

    protected function get_filtered_Course(Request $request)
    {
        return $this->service->get_filtered_Course($request);
    }


    //Corse Country Code



    protected function whatisLearnAboutList(Request $request)
    {
        return $this->service->whatisLearnAboutList($request);
    }
    protected function whatisLearnAboutCreate($id = null)
    {
        return $this->service->whatisLearnAbouCreate($id);
    }
    protected function whatisLearnAboutStore(CourseRequest $request)
    {
        //  dd($request->all());
        return $this->service->whatisLearnAbouStore($request);
    }
    protected function whatisLearnAboutView(Request $request)
    {
        return $this->service->whatisLearnAbouCreate($request->id, 'view');
    }
    protected function whatisLearnAboutEdit(Request $request)
    {
        return $this->service->whatisLearnAbouCreate($request->id);
    }
    protected function whatisLearnAboutUpdate(CourseRequest $request)
    {
        return $this->service->whatisLearnAbouStore($request);
    }
    protected function whatisLearnAboutDestroy(Request $request)
    {
        return $this->service->whatisLearnAbouDestroy($request->id);
    }

    protected function get_filtered_whatisLearnAbout(Request $request)
    {
        return $this->service->get_filtered_whatisLearnAbou($request);
    }

    //Corse Country Code
    //About Geography
    protected function aboutGeographyList(Request $request)
    {
        return $this->service->aboutGeographyList($request);
    }
    protected function aboutGeographyCreate($id = null)
    {
        return $this->service->aboutGeographyCreate($id);
    }


    //Course Read more
    protected function Course_ReadmoreList(Request $request)
    {
        return $this->service->CourseReadMoreList($request);
    }
    protected function Course_ReadmoreCreate($id = null)
    {
        return $this->service->aboutGeographyCreate($id);
    }
}
