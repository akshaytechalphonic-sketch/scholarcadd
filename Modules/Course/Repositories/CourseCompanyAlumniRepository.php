<?php

namespace Modules\Course\Repositories;
use App\Http\Controllers\Controller;
use Modules\Course\Models\Alumni;
use Illuminate\Support\Facades\DB;

class CourseCompanyAlumniRepository extends Controller
{
    protected $model;

    public function __construct(Alumni $model)
    {
        $this->model = $model;
    }

    public function CourseCompanyAlumniList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('Alumni', 'Course')::with('Coursedate','country','state_date')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function CourseCompanyAlumniFilters($query, $request)
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

        public function CourseCompanyAlumniCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect();

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('Alumni', 'Course')::with('Coursedate')->find($id);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                $multidat = Controller::model('Alumni', 'Course')
                    ::where('course_id', $data->course_id)
                    ->get();

            }

            $multidat = Controller::model('Alumni', 'Course')
                ::where('course_id', $data?->course_id)
                ->get();
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $states = Controller::model('State', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();

            return view('Course::CompanyAlumni.add', compact('data', 'multidat' ,'courses' , 'countries' , 'states' ));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course Alumni");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function CourseCompanyAlumniStore    ( $id = null , $data)
    {
        $model = Controller::model('Alumni', 'Course');
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
            $entry->Company_name = $data['Company_name'] ?? null;
            $entry->Company_link = $data['Company_link'] ;
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();
            // dd($entry);

            $message = $id
                ? 'Updated Course certificate for Course - ' . $data['course_id']
                : 'Created Course certificate for Course - ' . $data['course_id'];

            addToLog('Alumni', $data['course_id'], $message);

            return [
                'status' => true,
                'message' => 'Course Company Alumni saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course Alumni Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
