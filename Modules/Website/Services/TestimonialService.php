<?php

namespace Modules\Website\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Website\Repositories\TestimonialRepository;
use Yajra\DataTables\DataTables;

class TestimonialService extends Controller
{

 protected TestimonialRepository $repository;

    public function __construct(TestimonialRepository $repository)
    {
        $this->repository = $repository;
    }

    public function Testimonial_index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->TestimonialList();
                $query = $this->repository->TestimonialFilters($query, $request)->orderBy('id', 'desc');
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                        $q->orWhere('comment', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('Coursedate', function ($row) {
                        $view_url = route('faqs-course-view', ['id' => encrypt($row->id)]);

                        return '<a class="text-blue-500 " href="'.$view_url.'">'.$row?->Coursedate->course_title.'</a>';
                    })
                    ->addColumn('CountryName', function ($row) {
                        return $row?->CourseCountryName?->name;
                    })
                    ->addColumn('CountryIs02', function ($row) {
                        return $row?->CourseCountryName?->iso2;
                    })
                    ->addColumn('comment', function ($row) {
                        return Str::limit($row->comment ?? 'N/A', 30);
                    })
                    ->addColumn('name', function ($row) {
                        return $row->name ?? 'N/A';
                    })
                    ->addColumn('status', function ($row) {
                        return status_check($row->status);
                    })

                    ->addColumn('created_at', function ($row) {
                        return date_time_formate($row?->created_at);
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name;
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('testimonial-view', ['id' => $incr]);
                        $edit_url = route('testimonial-edit', ['id' => $incr]);
                        // $delete_url = route('testimonial-destroy', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="'.$view_url.'" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="'.$edit_url.'" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        // $dropdown .= '<li><a href="'.e($delete_url).'" class="block px-6 py-2 hover:bg-gray-100 text-red-600"><i class="ri-delete-bin-line mr-2"></i> Delete</a></li>';
                        $dropdown .= '</ul></div></div>';

                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'comment', 'status', 'CountryIs02', 'CountryName', 'name', 'Coursedate', 'created_by'])
                    ->make(true);
            }

            return view('website::testimonial.index');
        } catch (\Exception $e) {
            dd($e);
            catch_log($e, 'Getting list of Course Faqs data');

            return back()->with('error', $e->getMessage());
        }
    }

    // this mathod for Course FAQS Create methods create by Lal Singh
    public function TestimonialCreate(Request $request, $view = null)
    {

        try {

            $data = $this->repository->TestimonialCreate($request, $view);

            return $data;
        } catch (\Exception $e) {
            catch_log($e, 'Viewing/creating/Add FAQS Category ');

            return back()->with('error', $e->getMessage());
        }
    }

    // this mathod for Course FAQS Create methods create by Lal Singh
    public function TestimonialStore(Request $request)
    {
        try {
            $data = $this->repository->TestimonialStore($request);

            return redirect()->route('testimonial-list')->with('success', $data['message']);

        } catch (\Exception $e) {
            dd($e);
            catch_log($e, 'Creating testimonial');

            return back()->with('error', $e->getMessage());
        }
    }

    // this mathod for Course FAQS Destroy methods create by Lal Singh
    public function FaqsCourseDestroy($id)
    {
        try {
            $data = $this->repository->FaqCourseDestroy($id);
            if (! $data['status']) {
                return response()->json(['status' => false, 'error' => $data['message']]);
            }

            return redirect()->route('faqs-list-index')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Deleting Course Course');

            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getthedataofState($request)
    {
        return Controller::model('State', 'Course')::where('country_id', $request)->get();
    }
}
