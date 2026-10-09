<?php

namespace Modules\Website\Repositories;
use App\Http\Controllers\Controller;
use Modules\Website\Models\HiringCompanies;
use Illuminate\Support\Facades\DB;

class HiringRepository extends Controller
{
    protected $model;

    public function __construct(HiringCompanies $model)
    {
        $this->model = $model;
    }

     public function hiringList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('HiringCompanies', 'Website')::with('Coursedate','CourseCountryName','StateName');
    }

    public static function hiringFilters($query, $request)
    {
        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' && $request->custom_filter[0] != 'category', function ($query) use ($request) {
                $query->where($request->custom_filter[0], $request->custom_filter[1]);
            })
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by', function ($query) use ($request) {
                $item = $request->custom_filter[1];
                $query->whereHas('creator', function ($subQuery) use ($item) {
                    $subQuery->where('name', $item);
                });
            })
            ->when(
                $request && $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                function ($query) use ($request) {
                    $start_date = $request->custom_filter[1];
                    $end_date = $request->custom_filter[2];
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                }
            );
    }

    public function hiringCreate($id = null, $view = null)
    {

        try {
            $data = null;
            $multidat = collect();

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('HiringCompanies', 'website')::find($id);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                $multidat = Controller::model('HiringCompanies', 'website')
                    ::where('course_id', $data->course_id)
                    ->get();

            }





            $multidat = Controller::model('HiringCompanies', 'website')
                ::where('course_id', $data?->course_id)
                ->get();
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $states = Controller::model('State', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            return view('website::hiringCompanies.add', compact('courses', 'data', 'multidat' , 'countries' ,'states'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "hiring companies ");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function hiringStore( $id = null , $data)
    {
        $model = Controller::model('HiringCompanies', 'Website');
        $cmp_and_user = Controller::loginandcompany();
        try {
            if ($id) {
                $entry = $model::find($id);
                if (!$entry) {
                    return [
                        'status' => false,
                        'message' => __('Course not found with the given ID.'),
                    ];
                }
            } else {
                $entry = new $model();
            }

            $entry->course_id = $data['course_id'];
            $entry->country_id = $data['country_id'];
            $entry->state_id = $data['state_id'];
            $entry->company_name = $data['company_name'];
            $entry->job_role = $data['job_role'];
            $entry->job_description = $data['job_description'];
            $entry->job_link = $data['job_link'];
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();

            $message = $id
                ? 'Updated hiring companies for Course - ' . $data['course_id']
                : 'Created hiring comapnies for Course - ' . $data['course_id'];

            addToLog('HiringCompanies', $data['course_id'], $message);

            return [
                'status' => true,
                'message' => 'Hiring Companies saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Hiring Comapies Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


}
