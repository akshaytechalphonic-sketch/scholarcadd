<?php

namespace Modules\FAQS\Repositories;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FAQSRepository extends Controller
{
    // Repository logic here

    public function FaqsList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('CourseFaqs', 'FAQS')::with('Coursedata')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function FaqsFilters($query, $request)
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

    // this method for Course FAQS Store/update create by Lal Singh
    public function faqsCourseCreate($request, $view = null)
    {
        try {
            $data = null;
            $companyId = Controller::loginandcompany();
            $courseCategory = Controller::model('CourseCategory', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
            $courseCountry = Controller::model('CourseCountry', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
            //  Check if ID exists for edit or view
            if ($request->id) {
                $decryptedId = decrypt($request->id);
                $data = Controller::model('CourseFaqs', 'FAQS')::find($decryptedId);

                if (!$data) {
                    return redirect()->back()->with('error', 'FAQS Category not found with the given ID.');
                }
            }
            //  If request is for View Page
            if ($view === 'view') {
                return view('faqs::CourseFaqs.view', compact('data', 'courseCountry', 'courseCategory'));
            }
            //  Create or Edit Page View
            return view('faqs::CourseFaqs.add', compact('data', 'courseCountry', 'courseCategory'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "FAQS Course");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this method for Course FAQS  Store/update create by Lal Singh
    public function faqsCourseStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $model = Controller::model('CourseFaqs', 'FAQS');
        try {
            // ✅ UPDATE
            if (!empty($request->course_faqs_id)) {

                $id = decrypt($request->course_faqs_id);
                $entry = $model::find($id);

                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('FAQS Course not found with the given ID.'),
                    ];
                }
                // 🔹 Update existing record — NO NEW MODEL CREATION

                $entry->course_id = $request->course_category_id;
                $entry->country_id = $request->counrty_id;
                $entry->state_id = $request->sub_state_id;
                $entry->title = $request->title;
                $entry->description = $request->description;
                $entry->updated_by = $cmp_and_user[0];
                $entry->company_id = $cmp_and_user[1];
                $entry->save();

                return [
                    'status' => true,
                    'message' => 'FAQS Course updated successfully.',
                    'data' => $entry,
                ];
            }
            // ✅ CREATE
            $entry = new $model();
            $entry->course_id = $request->course_category_id;
            $entry->country_id = $request->counrty_id;
            $entry->state_id = $request->sub_state_id;
            $entry->title = $request->title;
            $entry->description = $request->description;
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();
            $message = 'Created FAQS Course for FAQS - ' . $request->course_id;
            addToLog('CourseFaqs', $request->course_id, $message);
            return [
                'status' => true,
                'message' => 'FAQS Course saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "CourseFaqs Course");

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    // this method for Course FAQS Destrory create by Lal Singh
    public function FaqCourseDestroy($request)
    {
        try {
            $id = decrypt($request->id);
            $model = Controller::model('CourseFaqs', 'FAQS');

            $entry = $model::find($id);

            if (!$entry) {
                return [
                    'status' => false,
                    'message' => 'FAQS Course not found.',
                ];
            }
            // Delete record
            $entry->delete();
            return [
                'status' => true,
                'message' => 'FAQS Course deleted successfully.',
            ];
        } catch (\Exception $e) {
            catch_log($e, "Delete FAQS Course");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}