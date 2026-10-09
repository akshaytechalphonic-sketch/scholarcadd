<?php

namespace Modules\Course\Repositories;
use App\Http\Controllers\Controller;
use Modules\Course\Models\CourseSEO;
use Illuminate\Support\Facades\DB;

class CourseSeoRepository extends Controller
{
    protected $model;

    public function __construct(CourseSEO $model)
    {
        $this->model = $model;
    }

    public function CourseSeoList()
    {
        $companyId = Controller::loginandcompany();
        return CourseSEO::query();
        }

    public static function CourseSeoFilters($query, $request)
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


    public function CourseSeoCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect();

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('CourseSEO', 'Course')::with('Coursedate')->find($id);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                $multidat = Controller::model('CourseSEO', 'Course')
                    ::where('course_id', $data->course_id)
                    ->get();

            }
            $multidat = Controller::model('CourseSEO', 'Course')
                ::where('course_id', $data?->course_id)
                ->get();
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $states = Controller::model('State', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            return view('Course::CourseSeo.add', compact('courses', 'data', 'multidat' , 'countries' ,'states'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course SEO");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function CourseSeoStore( $id = null , $data)
    {
        $model = Controller::model('CourseSEO', 'Course');
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
            $entry->who_this_course_image = $data['who_this_course_image'];
            $entry->eligibility_image = $data['eligibility_image'];
            $entry->exam_success = $data['exam_success'];
            $entry->more_info = $data['more_info'];
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();

            $message = $id
                ? 'Updated Course Seo for Course - ' . $data['course_id']
                : 'Created Course Seo for Course - ' . $data['course_id'];

            addToLog('CourseSEO', $data['course_id'], $message);

            return [
                'status' => true,
                'message' => 'Course SEO saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course SEO Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

}
