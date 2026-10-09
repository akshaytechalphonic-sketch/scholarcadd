<?php
namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCountry;
use Modules\DynamicMaster\Models\Country;
use Modules\DynamicMaster\Models\Feature;
use Modules\DynamicMaster\Models\Plan;
use Modules\DynamicMaster\Models\PlanMapping;
use Modules\DynamicMaster\Models\State;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlanMappingController extends Controller
{

    public function index()
    {
        return view('dynamicmaster::PlanMapping.index');

    }


    public function getData(Request $request)
{
    $plans = PlanMapping::with(['course', 'plan', 'country'])->orderByDesc('id');

    return DataTables::of($plans)
        ->addIndexColumn()
        ->addColumn('course_name', fn($row) => $row->course->course_title ?? 'N/A')
        ->addColumn('course_id', fn($row) => $row->course->id ?? 'N/A')
        ->addColumn('plan_name', fn($row) => $row->plan->name ?? 'N/A')
        ->addColumn('country_name', fn($row) => $row->country->name ?? 'N/A')
        ->addColumn('actions', function ($row) {
            $encryptedId = encrypt($row->id);
            $viewUrl = route('plan-mapping.view', ['id' => $encryptedId]);
            $editUrl = route('plan-mapping.edit', ['id' => $encryptedId]);
            $deleteUrl = route('plan-mapping.delete', ['id' => $encryptedId]);

            return '
            <div class="relative inline-block text-left dropdown">
                <button type="button" class="inline-flex items-center text-gray-600 hover:text-gray-900 focus:outline-none" onclick="toggleDropdown(this)">
                    <i class="ri-more-2-fill text-lg"></i>
                </button>
                <div class="dropdown-menu hidden absolute right-0 z-10 mt-2 w-44 origin-top-right rounded-md bg-white border border-gray-200 shadow-lg">
                    <ul class="py-1 text-sm text-gray-700">
                        <li>
                            <a href="' . $viewUrl . '" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                <i class="ri-eye-line mr-2"></i> View
                            </a>
                        </li>
                        <li>
                            <a href="' . $editUrl . '" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                <i class="ri-edit-2-line mr-2"></i> Edit
                            </a>
                        </li>
                          <li>
                                <a href="javascript:void(0);"
                                onclick="deleteRecord(\''.$deleteUrl.'\')"
                                class="flex items-center px-4 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </a>
                            </li>
                    </ul>
                </div>
            </div>
            ';
        })
        ->editColumn('status', fn($row) => $row->status == 'Active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>')

        ->filter(function ($query) use ($request) {
            if ($request->has('search') && $request->search['value']) {
                $search = strtolower($request->search['value']);
                $query->where(function ($q) use ($search) {
                    $q->whereHas('course', fn($sub) => $sub->whereRaw('LOWER(course_title) LIKE ?', ["%{$search}%"]))
                      ->orWhereHas('plan', fn($sub) => $sub->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]))
                      ->orWhereHas('course', fn($sub) => $sub->whereRaw('LOWER(id) LIKE ?', ["%{$search}%"]))
                      ->orWhere('course_fee', 'LIKE', "%{$search}%")
                      ->orWhere('discount', 'LIKE', "%{$search}%")
                      ->orWhereRaw('LOWER(status) LIKE ?', ["%{$search}%"]);
                });
            }
        })

        ->rawColumns(['actions', 'status'])
        ->make(true);
}



    public function create()
    {
        $course = Course::get();
        $plan = Plan::get();
        $country = CourseCountry::get();
        $features = Feature::get();

        // dd($plan);
        return view('dynamicmaster::PlanMapping.add', compact('course', 'plan', 'country', 'features'));
    }

    public function viewOrEdit(Request $request, $id)
    {


        if ($request?->is('*plan-mapping/view/*')) {
            $flag = 'View';
        } else if ($request?->is('*plan-mapping/edit/*')) {
            $flag = 'Edit';
        }
        $id = decrypt($id);
        $mapping = PlanMapping::findOrFail($id);
        $course = Course::get();
        $plan = Plan::get();
        $country = CourseCountry::get();
        $features = Feature::where('course_id', $mapping?->course_id)->orWhereNull('course_id')->get();

        // dd($plan->toArray());

        $countries = CourseCountry::get();
        return $flag ? view('dynamicmaster::PlanMapping.view', compact('course','features', 'plan', 'mapping', 'country', 'flag')) : abort(404, 'Page Not found');

    }

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'course_id' => 'required|exists:tbl_courses,id',
            'plan_id' => [
                'required',
                'exists:tbl_plans,id',
                Rule::unique('tbl_plan_mappings')
                    ->where('course_id', $request->course_id),
            ],
            'course_fee' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'tax' => 'required|numeric|min:0|max:100',
            'recommended' => 'required|in:yes,no',
        ], [
            'plan_id.unique' => 'This plan is already mapped to the selected course.',
        ]);

        PlanMapping::create([
            'course_id' => $validated['course_id'],
            'plan_id' => $validated['plan_id'],
            'course_fee' => $validated['course_fee'],
            'discount' => $validated['discount'],
            'tax' => $validated['tax'],
            'recommended' => $validated['recommended'],
        ]);

        return redirect()->route('plan-mapping-index')
            ->with('success', 'Plan mapping created successfully.');

    } catch (Exception $e) {
        Log::error('Error creating plan mapping: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', $e->getMessage());
    }
}



    public function update(Request $request, $id)
    {
        try {

            $validated = $request->validate([
                'course_id' => 'required|exists:tbl_courses,id',
                'plan_id' => 'required|exists:tbl_plans,id',
                'course_fee' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0|max:100',
                'tax' => 'required|numeric|min:0|max:100',
                // 'included_features' => 'required|array',
                // 'included_features' => 'string',
             ]);

            $id = decrypt($id);
            // Find existing mapping
            $mapping = PlanMapping::findOrFail($id);

            // Update mapping
            $mapping->update([
                'course_id' => $validated['course_id'],
                'plan_id' => $validated['plan_id'],
                'course_fee' => $validated['course_fee'],
                'discount' => $validated['discount'],
                'tax' => $validated['tax'],
                // 'included_features' => json_encode($validated['included_features']),
             ]);

            return redirect()->back()->with('success', 'Plan mapping updated successfully.');
        } catch (Exception $e) {
            dd($e);
            Log::error('Error updating plan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while updating the plan.');
        }
    }


    public function destroy($id)
    {
        try {
            $id = decrypt($id);

            $state = PlanMapping::findOrFail($id);

            $state->delete();

            if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'State deleted successfully.'
            ]);
        }

            return redirect()
                ->route('plan-mapping-index')
                ->with('success', 'State deleted successfully.');
        } catch (Exception $e) {
            dd($e);
            Log::error('Error deleting state: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred while deleting the state.');
        }
    }





    // Get features related to this course
    public function getFeatures($id)
    {
        $features = Feature::where('course_id', $id)->orWhereNull('course_id')->get();

        return response()->json($features);
    }



public function getIncludedFeatures(Request $request, $planId)
{
    $planId = (int) $planId;
    $courseId = $request->query('course_id');
    // 1️⃣ From FEATURES table (new system)
    $featureIncluded = Feature::where('plan_id', $planId)
        ->where('course_id', $courseId)
        ->where('is_excluded', 0)
        ->pluck('name')
        ->toArray();

    $featureExcluded = Feature::where('plan_id', $planId)
        ->where('course_id', $courseId)
        ->where('is_excluded', 1)
        ->pluck('name')
        ->toArray();

    // 2️⃣ From PLAN table JSON (old/default data)
    $plan = Plan::find($planId);

    $planIncluded = $plan
        ? json_decode($plan->included_features, true) ?? []
        : [];

    $planExcluded = $plan
        ? json_decode($plan->excluded_features, true) ?? []
        : [];

    // 3️⃣ MERGE both sources (FEATURE table has priority)
    $allIncluded = array_values(array_unique(array_merge($planIncluded, $featureIncluded)));
    $allExcluded = array_values(array_unique(array_merge($planExcluded, $featureExcluded)));

    return response()->json([
        'included' => $allIncluded,
        'excluded' => $allExcluded
    ]);
}



    public function getIncludedFeaturesForEdit(Request $request , $planId)
    {
        $planId = (int) $planId;
        $courseId = $request->query('course_id');

         $featureIncluded = Feature::where('plan_id', $planId)
        ->where('course_id', $courseId)
        ->where('is_excluded', 0)
        ->pluck('name')
        ->toArray();

        $featureExcluded = Feature::where('plan_id', $planId)
        ->where('course_id', $courseId)
        ->where('is_excluded', 1)
        ->pluck('name')
        ->toArray();

        return response()->json([
            'included' => $featureIncluded,
            'excluded' => $featureExcluded
        ]);
    }







}
