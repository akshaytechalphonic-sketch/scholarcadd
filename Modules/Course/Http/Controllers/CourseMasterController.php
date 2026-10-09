<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\CourseMasterService;
use Illuminate\Http\RedirectResponse;
use Modules\Course\Models\CourseCountry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Modules\Course\Models\State;
// use App\Helpers\CurrencyHelper;

class CourseMasterController extends Controller
{
    protected CourseMasterService $service;

    public function __construct(CourseMasterService $service)
    {
        $this->service = $service;
    }

    public function updateCurrencyRates(): RedirectResponse
    {
        $updated = $this->updateCountryCurrencyRates();

        return back()->with(
            $updated ? 'success' : 'error',
            $updated
                ? 'Currency rates updated successfully.'
                : 'Failed to update currency rates.'
        );
    }


    protected function CourseList(Request $request)
    {
        return $this->service->CourseList($request);
    }

    protected function CourseCreate($id = null)
    {
        return $this->service->CourseCreate($id);
    }

    protected function CourseStore(Request $request)
    {
        return $this->service->CourseStore($request);
    }

    protected function CourseView(Request $request)
    {
        return $this->service->CourseCreate($request->id, 'view');
    }

    protected function CourseEdit(Request $request)
    {
        return $this->service->CourseCreate($request->id);
    }

    protected function CourseUpdate(Request $request)
    {
        return $this->service->CourseStore($request);
    }

    protected function CourseDestroy(Request $request)
    {
        return $this->service->CourseDestroy($request->id);
    }

    protected function get_filtered_stateName(Request $request)
    {
        return $this->service->get_filtered_stateName($request);
    }

    // Corse Country Code

   //
    protected function addChildCategory(Request $request)
    {
        return $this->service->courseCategoryChildCreate($request); 
    }
    protected function courseChildcategoryEdit(Request $request)
    {
         return $this->service->courseChildcategoryEdit($request);
    }
    
    protected function couseChildcategoryStore(Request $request)
    {
         return $this->service->couseChildcategoryStore($request);
    }
    protected function couseChildcategoryUpdate(Request $request)
    {
        return $this->service->couseChildcategoryStore($request);
    }
    //	

    protected function countryList(Request $request)
    {
        return $this->service->countryList($request);
    }

    protected function countryCreate($id = null)
    {
        return $this->service->countryCreate($id);
    }

    protected function countryStore(Request $request)
    {
        return $this->service->countryStore($request);
    }

    protected function countryView(Request $request)
    {
        return $this->service->countryCreate($request->id, 'view');
    }

    protected function countryEdit(Request $request)
    {
        return $this->service->countryCreate($request->id);
    }

    protected function countryUpdate(Request $request)
    {
        return $this->service->countryStore($request);
    }


    protected function get_child(Request $request)
    {
        $data = masterChildData($request->parent_id);

        return response()->json($data);
    }

    // Corse Country Code

    protected function stateList(Request $request)
    {
        return $this->service->stateList($request);
    }

    protected function stateCreate($id = null)
    {

        return $this->service->stateCreate($id);
    }
        protected function stateStore(Request $request)
        {
            $result = $this->service->stateStore($request);
                if (!$result['status']) {
                    return back()->with('error', $result['message'])->withInput();
                }
                return redirect()->route('course-state-list')->with('success', $result['message']);
            }
        protected function stateEdit(Request $request)
         {

                return $this->service->stateCreate($request->id);
         }
        protected function stateUpdate(Request $request)
        {
       $result = $this->service->stateStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-state-list')->with('success', $result['message']);
    }

        protected function stateView(Request $request)
    {
        return $this->service->stateCreate($request->id, 'view');
    }

     public function download()
    {
        $states = State::all();

        $fileName = 'states_' . date('Y_m_d_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($states) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Name',
                'Country ID',
                'Status'
            ]);

            foreach ($states as $state) {
                fputcsv($file, [
                    $state->id,
                    $state->name,
                    $state->country_id ?? '',
                    $state->status ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    // Corse city Code

    protected function cityList(Request $request)
    {
        return $this->service->cityList($request);
    }

    protected function cityCreate($id = null)
    {
        return $this->service->cityCreate($id);
    }
    public function cityStore(Request $request)
    {
        $result = $this->service->CityStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-city-list')->with('success', $result['message']);
    }
    protected function cityEdit(Request $request)
    {
        return $this->service->cityCreate($request->id);
    }
    public function cityUpdate(Request $request)
    {
        $result = $this->service->CityStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-city-list')->with('success', $result['message']);
    }
    protected function cityView(Request $request)
    {
        return $this->service->stateCreate($request->id, 'view');
    }

    // course Category Create Code

    protected function coursecategoryList(Request $request)
    {
        return $this->service->CourseCategoryList($request);
    }

    protected function courseCategoryCreate(Request $request)
    {
        return $this->service->courseCategoryCreate($request);
    }

    protected function coursecategoryEdit(Request $request)
    {
        return $this->service->courseCategoryCreate($request);
    }

    protected function cousecategoryStore(Request $request)
    {
        return $this->service->cousecategoryStore($request);
    }

    protected function courseCategoryUpdate(Request $request)
    {
        return $this->service->cousecategoryStore($request);
    }


    function updateCountryCurrencyRates(): bool
    {
        try {
            $url = 'https://allratestoday.com/api/v1/rates';
            $apiKey = config('services.allratestoday.key');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey
            ])->get($url, [
                'source' => 'USD'
            ]);

            if ($response->failed()) {
                Log::error('Currency API failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }

            $rates = $response->json();
            $count = 0;
            // dd($rates);

            foreach ($rates as $rateObject) {
                CourseCountry::where('currency', $rateObject['target'])
                    ->where('status', 'Active')
                    ->update([
                        'value_in_dollar' => $rateObject['rate'],
                        'updated_at' => now()
                    ]);

            }



            return true;

        } catch (\Throwable $e) {
            Log::error('Currency update exception', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

}
