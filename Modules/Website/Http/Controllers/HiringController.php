<?php
namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Website\Models\HiringCompanies;
use Modules\Website\Services\HiringService;


class HiringController extends Controller
{
    protected HiringService $service;

    public function __construct(HiringService $service)
    {
        $this->service = $service;
    }
   // ================================= Hiring Companies ============================

    protected function hiringList(Request $request)
    {
        return $this->service->hiringList($request);
    }
    protected function hiringCreate(Request $request)
    {
        return $this->service->hiringCreate($request->id);
    }

    public function hiringStore(Request $request)
    {
        $result = $this->service->hiringStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('hiring-companies-list')->with('success', $result['message']);
    }

        protected function hiringEdit(Request $request)
    {
        return $this->service->hiringCreate($request->id);
    }

    protected function hiringUpdate(Request $request)
    {
       $result = $this->service->hiringStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('hiring-companies-list')->with('success', $result['message']);
    }

    public function downloadHiringCompanies()
    {
        $alumnis = HiringCompanies::all();

        $fileName = 'hiring_companies_' . date('Y_m_d_His') . '.csv';

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
                'Job Role',
                'Job Description',
                'Job Link',
            ]);

            foreach ($alumnis as $state) {
                fputcsv($file, [
                    $state->id,
                    $state->country_id ?? '',
                    $state->company_name,
                    $state->job_role ?? '',
                    $state->job_description ?? '',
                    $state->job_link ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
