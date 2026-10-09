<?php

namespace Modules\DynamicMaster\Repositories;
use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Models\TeamApplication;
use Illuminate\Support\Facades\DB;



class TeamApplicationRepository extends Controller
{
    protected $model;

    public function __construct(TeamApplication $model)
    {
        $this->model = $model;
    }

    public function ApplicationList()
    {
        $companyId = Controller::loginandcompany();
         return Controller::model('TeamApplication', 'DynamicMaster')::with('Coursedate','CourseCountryName')->orderby('id', 'DESC');
        }

    public static function ApplicationFilters($query, $request)
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
}
