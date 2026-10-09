<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Course\Services\CourseYouTubeService;
use Illuminate\Http\Request;
use Modules\Course\Http\Requests\CourseRequest;


class CourseYouTubeController extends Controller
{
    protected CourseYouTubeService $service;

    public function __construct(CourseYouTubeService $service)
    {
        $this->service = $service;

    }
    protected function CourseYoutubeList(Request $request)
    {
        return $this->service->courseYouTubeList($request);
    }
    protected function courseYouTubeCreate($id = null)
    {
        return $this->service->courseYouTubeCreate($id);
    }
    protected function CourseYoutubeStore(Request $request)
    {
        return $this->service->CourseYoutubeStore($request);
    }
    protected function CourseYoutubeleView(Request $request)
    {
        return $this->service->courseYouTubeCreate($request->id, 'view');
    }
    protected function CourseYoutubeEdit(Request $request)
    {
        return $this->service->courseYouTubeCreate($request->id);
    }
    protected function CourseYoutubeUpdate(Request $request)
    {
        return $this->service->CourseYoutubeStore($request);
    }
    protected function CourseYoutubeDestroy(Request $request)
    {
        return $this->service->CourseDestroy($request->id);
    }

    protected function get_filtered_Course(Request $request)
    {
        return $this->service->get_filtered_Course($request);
    }
    protected function updateFromExcel(Request $request)
    {
         return $this->service->updateFromExcel($request);
    }


}
