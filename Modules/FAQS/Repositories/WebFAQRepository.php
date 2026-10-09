<?php

namespace Modules\FAQS\Repositories;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WebFAQRepository extends Controller
{
    // Repository logic here


    public function FaqsindexList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('FaqsGeneral', 'FAQS')::with('Coursedate')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }
    public static function FaqsindexFilters($query, $request)
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

    // this method for FAQS General Store/update create by Lal Singh
    public function faqsGeneralCreate($request, $view = null)
    {
        try {
            $data = null;
            $companyId = Controller::loginandcompany();
            $faqsCategory = Controller::model('FAQS_Category', 'FAQS')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
            $courseCountry = Controller::model('Course', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
            //  Check if ID exists for edit or view
            if ($request->id) {
                $decryptedId = decrypt($request->id);
                $data = Controller::model('FaqsGeneral', 'FAQS')::find($decryptedId);

                if (!$data) {
                    return redirect()->back()->with('error', 'FAQS Category not found with the given ID.');
                }
            }
            //  If request is for View Page
            if ($view === 'view') {
                return view('faqs::Faqs.view', compact('data', 'faqsCategory', 'courseCountry'));
            }
            //  Create or Edit Page View
            return view('faqs::Faqs.add', compact('data', 'faqsCategory', 'courseCountry'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "FAQS Category");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // this method for FAQS General Store/update create by Lal Singh
    public function faqsGeneralStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $model = Controller::model('FaqsGeneral', 'FAQS');
        try {
            // ✅ UPDATE
            if (!empty($request->faqsGeneral_id)) {

                $id = decrypt($request->faqsGeneral_id);
                $entry = $model::find($id);

                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('FAQS Category not found with the given ID.'),
                    ];
                }
                // 🔹 Update existing record — NO NEW MODEL CREATION

                $entry->course_id = $request->course_category_id;
                $entry->category_id = $request->faqs_category_id;
                $entry->title = $request->faqs_title;
                $entry->description = $request->description;
                $entry->updated_by = $cmp_and_user[0];
                $entry->company_id = $cmp_and_user[1];
                $entry->save();

                return [
                    'status' => true,
                    'message' => 'FAQS General updated successfully.',
                    'data' => $entry,
                ];
            }
            // ✅ CREATE
            $entry = new $model();
            $entry->course_id  = $request->course_category_id;
            $entry->category_id = $request->faqs_category_id;
            $entry->title = $request->faqs_title;
            $entry->description = $request->description;
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();
            $message = 'Created FAQS General for FAQS - ' . $request->course_id;
            addToLog('FAQS', $request->course_id, $message);
            return [
                'status' => true,
                'message' => 'FAQS General saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "FAQS General");

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    // this method for FAQS General Destrory create by Lal Singh
    public function FaqsGeneralDestroy($request)
    {
        try {
            $id = decrypt($request->id);
            $model = Controller::model('FaqsGeneral', 'FAQS');

            $entry = $model::find($id);

            if (!$entry) {
                return [
                    'status' => false,
                    'message' => 'FAQS General not found.',
                ];
            }
            // Delete record
            $entry->delete();
            return [
                'status' => true,
                'message' => 'FAQS General deleted successfully.',
            ];
        } catch (\Exception $e) {
            catch_log($e, "Delete FAQS General");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // faqs category for genral faqs catefory create by Lal Singh

    public function FaqsCategoryindexList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('FAQS_Category', 'FAQS')::where('company_id', $companyId[1])->orderby('id', 'DESC');
    }
    public static function FaqsCategoryindexFilters($query, $request)
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

    public function faqsCetegoryCreate($request, $view = null)
    {
        try {
            $data = null;
            //  Check if ID exists for edit or view
            if ($request->id) {
                $decryptedId = decrypt($request->id);
                $data = Controller::model('FAQS_Category', 'FAQS')::find($decryptedId);

                if (!$data) {
                    return redirect()->back()->with('error', 'FAQS Category not found with the given ID.');
                }
            }
            //  If request is for View Page
            if ($view === 'view') {
                return view('faqs::FaqsCategory.view', compact('data'));
            }
            //  Create or Edit Page View
            return view('faqs::FaqsCategory.add', compact('data'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "FAQS Category");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function faqs_categoryStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $model = Controller::model('FAQS_Category', 'FAQS');
        try {
            // ✅ UPDATE
            if (!empty($request->faqs_categotyId)) {

                $id = decrypt($request->faqs_categotyId);
                $entry = $model::find($id);

                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('FAQS Category not found with the given ID.'),
                    ];
                }
                // 🔹 Update existing record — NO NEW MODEL CREATION
                $entry->category_name = $request->faqs_category;
                $entry->updated_by = $cmp_and_user[0];
                $entry->company_id = $cmp_and_user[1];
                $entry->save();

                return [
                    'status' => true,
                    'message' => 'FAQS Category updated successfully.',
                    'data' => $entry,
                ];
            }
            // ✅ CREATE
            $entry = new $model();
            $entry->category_name = $request->faqs_category;
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();
            $message = 'Created FAQS Category for FAQS - ' . $request->faqs_categotyId;
            addToLog('FAQS_Category', $request->faqs_categotyId, $message);
            return [
                'status' => true,
                'message' => 'FAQS Category saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "FAQS Category");

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function FaqsCetegoryDestroy($request)
    {
        try {
            $id = decrypt($request->id);
            $model = Controller::model('FAQS_Category', 'FAQS');

            $entry = $model::find($id);

            if (!$entry) {
                return [
                    'status' => false,
                    'message' => 'FAQS Category not found.',
                ];
            }
            // Delete record
            $entry->delete();
            return [
                'status' => true,
                'message' => 'FAQS Category deleted successfully.',
            ];
        } catch (\Exception $e) {
            catch_log($e, "Delete FAQS Category");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}