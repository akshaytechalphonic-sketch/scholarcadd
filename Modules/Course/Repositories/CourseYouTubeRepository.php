<?php

namespace Modules\Course\Repositories;

use App\Http\Controllers\Controller;

class CourseYouTubeRepository extends Controller
{
    public function CourseYoutubeList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('CourseYouTube', 'Course')::with('Coursedate')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function CourseYoutubeFilters($query, $request)
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

    public function courseYouTubeCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect(); // default empty collection

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('CourseYouTube', 'Course')::with('Coursedate')->find($id);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                // ✅ Get all videos under the same course
                $multidat = Controller::model('CourseYouTube', 'Course')
                    ::where('course_id', $data->course_id)
                    ->get();

                if ($view === 'view') {
                    $course = Controller::model('Course', 'Course')::get();
                    return view('Course::CourseYouTube.view', compact('data', 'multidat', 'course'));
                }
            }
            $multidat = Controller::model('CourseYouTube', 'Course')
                ::where('course_id', $data?->course_id)
                ->get();
            $course = Controller::model('Course', 'Course')::get();
            return view('Course::CourseYouTube.add', compact('course', 'data', 'multidat'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course YouTube");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function CourseYoutubeStore($request)
    {

        $cmp_and_user = Controller::loginandcompany();

        $model = Controller::model('CourseYouTube', 'Course');

        try {
            // ✅ UPDATE
            if (!empty($request->course_youtubeId)) {
                $id = decrypt($request->course_youtubeId);
                $entry = $model::find($id);

                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('Course not found with the given ID.'),
                    ];
                }

                // delete old YouTube records for this course
                $model::where('course_id', $entry->course_id)->delete();

                // insert new YouTube records
                foreach ($request->video_link as $key => $videolink) {
                    if ($videolink == null) {
                        $model::where('course_id', $entry->course_id)->delete();
                    } else {
                        $newEntry = new $model();
                        $newEntry->course_id = $request->course_id;
                        $newEntry->video_url = $videolink;
                        $newEntry->video_description = $request->video_description[$key] ?? null;
                        $newEntry->created_by = $cmp_and_user[0];
                        $newEntry->company_id = $cmp_and_user[1];
                        $newEntry->save();
                    }
                }
                return [
                    'status' => true,
                    'message' => 'Course YouTube saved Updated.',
                    'data' => $newEntry,
                ];
            } else {
                // ✅ CREATE (multiple videos)
                foreach ($request->video_link as $key => $videolink) {
                    $entry = new $model();
                    $entry->course_id = $request->course_id;
                    $entry->video_url = $videolink;
                    $entry->video_description = $request->video_description[$key] ?? null;
                    $entry->created_by = $cmp_and_user[0];
                    $entry->company_id = $cmp_and_user[1];
                    $entry->save();
                }

                $message = 'Created Course YouTube(s) for Course - ' . $request->course_id;
            }
            addToLog('CourseYouTube', $request->course_id, $message);
            return [
                'status' => true,
                'message' => 'Course YouTube saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course YouTube");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function CourseDestroy($id)
    {
        try {
            $id = decrypt($id);
            $model = Controller::model('CourseYouTube', 'Course');
            $entry = $model::find($id);

            if (!$entry) {
                return [
                    'status' => false,
                    'message' => 'YouTube not found.',
                ];
            }

            $entry->delete();
            return [
                'status' => true,
                'message' => 'Youtube deleted successfully.',
            ];
        } catch (\Exception $e) {
            catch_log($e, "Delete Course Youtube");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
