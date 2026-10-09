<?php

namespace Modules\FAQS\Services;

use Modules\FAQS\Repositories\FAQSRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FAQSService extends Controller
{
    protected FAQSRepository $repository;

    public function __construct(FAQSRepository $repository)
    {
        $this->repository = $repository;
    }

    public function Faqscourse_index(Request $request)
    {

        try {
            if ($request->ajax()) {
                $query = $this->repository->FaqsList();
                $query = $this->repository->FaqsFilters($query, $request)->orderBy('id', 'desc');
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%$search%");
                         $q->orWhere('description', 'like', "%$search%");

                    });
                }
                return DataTables::of($query)
                    ->addColumn('coursedata', function ($row) {
                        return  $row?->Coursedata?->category_name;
                    })
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description ?? 'N/A', 30);
                    })
                    ->addColumn('title', function ($row) {
                        return Str::limit($row->title ?? 'N/A', 70);
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_time_formate($row?->created_at);
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name;
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('faqs-course-view', ['id' => $incr]);
                        $edit_url = route('faqs-course-edit', ['id' => $incr]);
                        $delete_url = route('faqs-course-destroy', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        $dropdown .= '<li>
                                <a href="javascript:void(0);"
                                onclick="deleteRecord(\''.$delete_url.'\')"
                                class="block px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </a>
                            </li>';
                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'description', 'title', 'coursedata', 'created_by'])
                    ->make(true);
            }
            return view('faqs::CourseFaqs.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Faqs data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }
     // this mathod for Course FAQS Create methods create by Lal Singh
    public function faqsCourseCreate(Request $request, $view = null)
    {
         try {
            $data = $this->repository->faqsCourseCreate($request, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Add FAQS Category ");
            return back()->with('error', $e->getMessage());
        }
    }
     // this mathod for Course FAQS Create methods create by Lal Singh
    public function FaqsCourseStore(Request $request)
    {
             try {
            $data = $this->repository->faqsCourseStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('faqs-list-index')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Course Course");
            return back()->with('error', $e->getMessage());
        }
    }


     // this mathod for Course FAQS Destroy methods create by Lal Singh
    public function FaqsCourseDestroy($id)
    {
        try {
            $data = $this->repository->FaqCourseDestroy($id);
             if (request()->ajax()) {
            return response()->json($data);
        }
            if (!$data['status']) {
                return response()->json(['status' => false, 'error' => $data['message']]);
            }
            return redirect()->route('faqs-list-index')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Deleting Course Course");
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getthedataofState($request)
    {
        return Controller::model('State', 'Course')::where('country_id', $request)->get();
    }

}
