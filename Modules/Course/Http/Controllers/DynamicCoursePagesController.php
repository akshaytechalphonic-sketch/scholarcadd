<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\DynamicCoursePageService;

class DynamicCoursePagesController extends Controller
{
    protected DynamicCoursePageService $service;

    public function __construct(DynamicCoursePageService $service)
    {
        $this->service = $service;

    }

    protected function DynamicCoursePagesList(Request $request)
    {
        return $this->service->DynamicCoursePagesList($request);
    }

    protected function DynamicCoursePagesCreate($id = null)
    {

        return $this->service->DynamicCoursePagesCreate($id);
    }

    protected function DynamicCoursePagesStore(Request $request)
    {

        return $this->service->DynamicCoursePagesStore($request);
    }

    protected function DynamicCoursePagesUpdate(Request $request)
    {
        return $this->service->DynamicCoursePagesStore($request);
    }

    protected function DynamicCoursePagesDestroy(Request $request)
    {
        $result = $this->service->CourseDynamicPageDestroy($request->id);

        if (request()->ajax()) {
            return response()->json($result);
        }

        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('dynamic-course-pages-list')->with('success', $result['message']);
    }

    protected function CourseYoutubeStore(Request $request)
    {
        return $this->service->CourseYoutubeStore($request);
    }

    protected function CourseYoutubeleView(Request $request)
    {
        return $this->service->courseYouTubeCreate($request->id, 'view');
    }

    protected function DynamicCoursePagesEdit(Request $request)
    {
        return $this->service->DynamicCoursePagesCreate($request?->id);
    }

    protected function CourseYoutubeUpdate(Request $request)
    {
        return $this->service->CourseYoutubeStore($request);
    }

    protected function CourseDestroy(Request $request)
    {
        return $this->service->CourseDestroy($request->id);
    }

    protected function get_filtered_Course(Request $request)
    {
        return $this->service->get_filtered_Course($request);
    }

    //  Course Trainer methods create by Lal Singh

    protected function coursetrainerPagesList(Request $request)
    {
        return $this->service->coursetrainerPagesList($request);
    }

    protected function coursetrainerPagesCreate(Request $request)
    {
        return $this->service->coursetrainerCreate($request);
    }

    protected function coursetrainerPagesStore(Request $request)
    {
        return $this->service->coursetrainerStore($request);
    }

    protected function coursetrainerPagesUpdate(Request $request)
    {
        return $this->service->coursetrainerStore($request);
    }

    protected function coursetrainerDestroy(Request $request)
    {
        return $this->service->CourseDestroy($request->id);
    }

    protected function DynamicPagesData(Request $request)
    {
        return $this->service->DynamicPagesData($request);
    }

    public function getStates(Request $request)
    {
        $country = Controller::model('CourseCountry', 'Course')::where('name', $request->country_id)
            ->first();

        if (! $country) {
            return response()->json(['states' => []]);
        }

        $states = Controller::model('State', 'Course')::where('country_id', $country->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'states' => $states,
        ]);
    }

    
}
