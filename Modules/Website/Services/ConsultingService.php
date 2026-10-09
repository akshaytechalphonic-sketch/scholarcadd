<?php

namespace Modules\Website\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Website\Repositories\ConsultingRepository;
use Yajra\DataTables\DataTables;

class ConsultingService extends Controller
{
    protected ConsultingRepository $repository;

    public function __construct(ConsultingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function consultingindex(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->consultingList();
                $query = $this->repository->ConsultingFilters($query, $request)->orderBy('id', 'desc');
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                        $q->orWhere('comment', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('banner_Heading', function ($row) {
                        return $row?->banner_Heading ;
                    })
                    ->addColumn('status', function ($row) {
                        return status_check($row->status);
                    })

                    ->addColumn('meta_description', function ($row) {
                        return Str::words($row?->meta_description, 30, '...');
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name ?? 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $edit_url = route('consulting-edit', ['consulting_id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                    
                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'title', 'status', 'meta_description', 'created_by'])
                    ->make(true);
            }

            return view('website::consulting.index');
        } catch (\Exception $e) {
            dd($e);
            catch_log($e, 'Getting list of Course Faqs data');

            return back()->with('error', $e->getMessage());
        }
    }

    // this mathod for Course FAQS Create methods create by Lal Singh
  public function consultingCreate(Request $request)
    {
        $model = Controller::model('Consulting', 'Website');
        $data = null;
        if ($request->filled('consulting_id')) {
            $id = decrypt($request->consulting_id);
            $data = $model::with('consulting')->find($id);

        }
       
        return view('website::consulting.add', [
            'data' => $data
        ]);
    }


    // this mathod for Course FAQS Create methods create by Lal Singh
    public function consultingStore(Request $request)
    {
        try {
            $data = $this->repository->consultingStore($request);

            return redirect()->route('consulting-list')->with('success', $data['message']);

        } catch (\Exception $e) {
            dd($e);
            catch_log($e, 'Creating consulting');

            return back()->with('error', $e->getMessage());
        }
    }

    // this mathod for Course FAQS Destroy methods create by Lal Singh
    public function consultingDestroy($id)
    {
        try {
            $data = $this->repository->FaqCourseDestroy($id);
            if (!$data['status']) {
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
