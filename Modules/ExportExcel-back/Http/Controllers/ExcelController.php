<?php

namespace Modules\ExportExcel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ExportExcel\Services\ExcelService;


class ExcelController extends Controller
{
    protected ExcelService $service;

    public function __construct(ExcelService $service)
    {
        $this->service = $service;

    }

    protected function updateFromExcel(Request $request)
    {
         return $this->service->updateFromExcel($request);
    }

     protected function updateCourseHighlights(Request $request)
    {
         return $this->service->updateCourseHighlights($request);
    }

        protected function updateCourseWhyChooseUs(Request $request)
    {
         return $this->service->updateCourseWhyChooseUs($request);
    }

        protected function updateCourseEligibility(Request $request)
    {
         return $this->service->updateCourseEligibility($request);
    }

        protected function updateCourseRoadmap(Request $request)
    {
         return $this->service->updateCourseRoadmap($request);
    }

        protected function updateCourseLearningObjective(Request $request)
    {
         return $this->service->updateCourseLearningObjective($request);
    }

        protected function updateCourseAboutGeography(Request $request)
    {
         return $this->service->updateCourseAboutGeography($request);
    }

    protected function downloadFromFormateExcel(Request $request)
    {
         return $this->service->downloadFromFormateExcel($request);
    }

    protected function downloadPointerFormateCsv(Request $request)
    {
         return $this->service->downloadPointerFormateCsv($request);
    }

    protected function downloadHighlightsFormateCsv(Request $request)
    {
         return $this->service->downloadHighlightsFormateCsv($request);
    }

    protected function downloadWhyChooseUsFormateCsv(Request $request)
    {
         return $this->service->downloadWhyChooseUsFormateCsv($request);
    }


    protected function downloadEligiblityFormateCsv(Request $request)
    {
         return $this->service->downloadEligiblityFormateCsv($request);
    }

    protected function downloadRoadmapFormateCsv(Request $request)
    {
         return $this->service->downloadRoadmapFormateCsv($request);
    }

    protected function downloadObjectiveFormateCsv(Request $request)
    {
         return $this->service->downloadLearningObjectiveFormateCsv($request);
    }

    protected function downloadGeographyFormateCsv(Request $request)
    {
         return $this->service->downloadAboutGeographyFormateCsv($request);
    }

     protected function updateFAQS(Request $request)
    {
         return $this->service->updateFAQS($request);
    }

    protected function downloadFAQSFormateExcel(Request $request)
    {
         return $this->service->downloadFAQSFormateExcel($request);
    }

    protected function updateState(Request $request)
    {
         return $this->service->updateState($request);
    }

        protected function downloadStateFormateExcel(Request $request)
    {
         return $this->service->downloadStateFormateExcel($request);
    }
}

