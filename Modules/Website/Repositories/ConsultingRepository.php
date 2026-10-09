<?php

namespace Modules\Website\Repositories;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class ConsultingRepository extends Controller
{
    // Consulting Code

    public function ConsultingList()
    {

        return Controller::model('Consulting', 'Website')::orderby('id', 'DESC');
    }

    public static function ConsultingFilters($query, $request)
    {

        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by', function ($query) use ($request) {
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



    public function ConsultingStore($request)
    {

        $cmp_and_user = Controller::loginandcompany();
        try {
            Controller::db()::beginTransaction();
            $model = Controller::model('Consulting', 'Website');
            $id = null;
            if ($request->consulting_id) {
                $id = decrypt($request->consulting_id);
                $entry = $model::find($id);
                if (! $entry) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Pages not found with the given ID.'),
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Pages-'.$entry->banner_Heading;
            } else {
                $entry = new $model;

                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Pages-'.$request->banner_Heading;
            }
            $entry->banner_Heading = $request->banner_Heading;
            $entry->section_one_Heading = $request->section_one_Heading;
            $entry->banner_content = $request->banner_content;
            $entry->meta_title = $request->meta_title;
            $entry->meta_description = $request->meta_description;
            $entry->section_one_content = $request->section_one_content;
            $entry->section_two_Heading = $request->section_two_Heading;
            $entry->section_two_sub_Heading = $request->section_two_sub_Heading;
            $entry->base_text = $request->base_text;
            $entry->chooseus_Heading = $request->chooseus_Heading;
            $entry->choose_us_content = $request->choose_us_content;
            $entry->pointer =  $request->pointer;
            $entry->advantages =  $request->advantages;
            $entry->faqs_contect = $request->faqs_contect;
            $entry->company_id = $cmp_and_user[1];
            $entry->status = "Active";
            $entry->save();

            if ($request->hasFile('page_image')) {

                $image = Controller::helper('CommonHelper')::folder_name($entry->id, 'Consulting', 'Consulting', $request->file('page_image'), 15, 'images', 'Website');

                if (empty($image)) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }
                $entry->pages_img = $image[1];
                $entry->save();
            }

        $model = Controller::model('SubConsulting', 'Website');
        // 🔴 1. Delete existing records
        $model::where('consultingid', $entry->id)->delete();

        // 🔵 2. Insert new records
        foreach ($request->title as $key => $title) {

            $SubConsulting = new $model;
            $SubConsulting->consultingid  = $entry->id; // ✅ parent ID
            $SubConsulting->title         = $title;
            $SubConsulting->content       = $request->content[$key] ?? null;
            $SubConsulting->save();
        }
            Controller::db()::commit();
            addToLog('Consulting', $entry->id, $message);
            return [
                'status' => true,
                'message' => 'Consulting saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            //   dd($e);
            Controller::db()::rollback();
            catch_log($e, 'Consulting');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


}
