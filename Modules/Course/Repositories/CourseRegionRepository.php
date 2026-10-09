<?php

namespace Modules\Course\Repositories;
use App\Http\Controllers\Controller;
use Modules\Course\Models\CourseRegion;
use Illuminate\Support\Facades\DB;
use Modules\Course\Models\Course;

class CourseRegionRepository extends Controller
{
    protected $model;

    public function __construct(CourseRegion $model)
    {
        $this->model = $model;
    }

    public function RegionList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('CourseRegion', 'Course')::with('country')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function RegionFilters($query, $request)
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

     public function RegionCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect();

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('CourseRegion', 'Course')::with('country')->find($id);
                if ($data && $data->Country) {
                $data->country_array = json_decode($data->Country, true) ?? [];
            }
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

            }
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            // dd($data);
            return view('Course::Region.create', compact('courses', 'data' , 'countries' ));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course promo Code");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function RegionStore( $id = null , $data)
    {
        // dd($data);
        $model = Controller::model('CourseRegion', 'Course');
        $cmp_and_user = Controller::loginandcompany();
        try {
            if ($id) {
                $entry = $model::find($id);
                if (!$entry) {
                    return [
                        'status' => false,
                        'message' => __('Region not found with the given ID.'),
                    ];
                }
            } else {
                $entry = new $model();
            }

            $entry->name = $data['name'];
            $entry->status = $data['status'];
            $entry->country = json_encode($data["country_id"]);
            $entry->description = $data["description"];
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();

            $message = $id
                ? 'Updated Course certificate for Course - '
                : 'Created Course certificate for Course - ';

            return [
                'status' => true,
                'message' => 'Course Region saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course Region  Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
