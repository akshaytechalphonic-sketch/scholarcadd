<?php

namespace Modules\Enrolled\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Enrolled\Services\EnrolledCourseService;
use Illuminate\Http\Request;

class EnrolledCourseCorporateController extends Controller
{
    protected EnrolledCourseService $service;

    public function __construct(EnrolledCourseService $service)
    {
        $this->service = $service;
    }

    protected function CorporatenewDataindex(Request $request)
    {
        return $this->service->CourseCorporateindex($request);
    }
    protected function CourseIndividualindex(Request $request)
    {
        return $this->service->CourseIndividualindex($request);
    }
    protected function enrollcourseCorporateview(Request $request)
    {
        return $this->service->enrollcourseCorporateview($request);
    }
    
    

    
    
}
