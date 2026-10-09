<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Models\Alumni;
use Modules\Course\Services\CourseCompanyAlumniService;

class CourseCompanyAlumniController extends Controller
{
    protected CourseCompanyAlumniService $service;

    public function __construct(CourseCompanyAlumniService $service)
    {
        $this->service = $service;
    }

    protected function CourseCompanyAlumniList(Request $request)
    {
        return $this->service->CourseCompanyAlumniList($request);

    }

     protected function CourseCompanyAlumniCreate($id = null)
    {

        return $this->service->CourseCompanyAlumniCreate($id);
    }

    public function CourseCompanyAlumniStore(Request $request)
    {
        $result = $this->service->CourseCompanyAlumniStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-company-alumni-list')->with('success', $result['message']);
    }

    protected function CourseCompanyAlumniEdit(Request $request)
    {
       $result =  $this->service->CourseCompanyAlumniCreate($request->id);

       return $result;
    }

    protected function CourseCompanyAlumniUpdate(Request $request)
    {
       $result = $this->service->CourseCompanyAlumniStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-company-alumni-list')->with('success', $result['message']);
    }

     public function downloadAlumni()
    {
        $alumnis = Alumni::all();

        $fileName = 'alumni_' . date('Y_m_d_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($alumnis) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Country ID',
                'Company Name',
                'Company Link',
            ]);

            foreach ($alumnis as $state) {
                fputcsv($file, [
                    $state->id,
                    $state->country_id ?? '',
                    $state->company_name,
                    $state->company_link ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }






}
