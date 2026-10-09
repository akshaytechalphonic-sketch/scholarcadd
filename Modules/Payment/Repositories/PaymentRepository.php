<?php

namespace Modules\Website\Repositories;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends Controller
{
    protected $model;

    public function __construct(HiringCompanies $model)
    {
        $this->model = $model;
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
}
