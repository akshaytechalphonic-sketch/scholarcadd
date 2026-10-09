<?php
namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Modules\DynamicMaster\Models\Feature;
use Modules\DynamicMaster\Models\Plan;
use Modules\DynamicMaster\Models\State;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class PlanController extends Controller
{

    public function index()
    {
        $plans = Plan::get();
        return view('dynamicmaster::Plan.index', compact('plans'));

    }

    public function getData()
    {
        // $plans = Plan::query();
        $plans = Plan::query()->orderBy('created_at', 'desc');


        return DataTables::of($plans)
            ->addColumn('serial', function ($row) {
                return $row->rowIndex + 1;
            })

            ->addColumn('actions', function ($row) {
                $encryptedId = encrypt($row->id);
                $viewUrl = route('plan.view', ['id' => $encryptedId]);
                $editUrl = route('plan.edit', ['id' => $encryptedId]);
                $deleteUrl = route('plan.delete', ['id' => $encryptedId]);

                return '
                <div class="relative inline-block text-left dropdown">
                    <button type="button" class="inline-flex items-center text-gray-600 hover:text-gray-900 focus:outline-none" onclick="toggleDropdown(this)">
                        <i class=\'ri-more-2-fill text-lg\'></i>
                    </button>
                    <div class="dropdown-menu hidden absolute right-0 z-10 mt-2 w-44 origin-top-right rounded-md bg-white border border-gray-200 shadow-lg">
                        <ul class="py-1 text-sm text-gray-700">
                            <li>
                                <a href="' . $viewUrl . '" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class=\'ri-eye-line mr-2\'></i> View
                                </a>
                            </li>
                            <li>
                                <a href="' . $editUrl . '" class="flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class=\'ri-edit-2-line mr-2\'></i> Edit
                                </a>
                            </li>
                            <li>

                            <form action="' . $deleteUrl . '" method="POST">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <button type="submit" class="flex items-center px-4 py-2 hover:bg-gray-100 text-red-600">
                                        <i class=\'ri-delete-bin-line mr-2\'></i> Delete
                                    </button>
                                </form>

                            </li>
                        </ul>
                    </div>
                </div>
            ';
            })
            ->editColumn('status', function ($row) {
                return $row->status == 'Active'
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->rawColumns(['actions', 'status'])
            ->make(true);
    }

    public function create()
    {
        // dd($countries);
        $features = Feature::whereNull('course_id')->get();
        return view('dynamicmaster::Plan.add', compact('features'));
    }

    public function viewOrEdit(Request $request, $id)
    {


        if ($request?->is('*plans/view/*')) {
            $flag = 'View';
        } else if ($request?->is('*plans/edit/*')) {
            $flag = 'Edit';
        }
        $id = decrypt($id);
        $plan = Plan::findOrFail($id);
        $features = Feature::whereNull('course_id')->get();

        // dd($plan);


        // dd($plan->toArray());

        return $flag ? view('dynamicmaster::Plan.view', compact('plan', 'features', 'flag')) : abort(404, 'Page Not found');

    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                // 'included-features' => 'nullable|array',
                'included-features.*' => 'string',
                // 'excluded-features' => 'nullable|array',
                'excluded-features.*' => 'string',
                'baseDiscount' => 'required|numeric|min:0|max:99',
                'recommended' => 'required|in:yes,no',
                'is_basic' => 'required|in:yes,no',
            ]);

            $plan = Plan::create([
                'name' => $validated['name'],
                'included_features' => empty($validated['included-features']) ? null : json_encode($validated['included-features']),
                'excluded_features' => empty($validated['excluded-features']) ? null : json_encode($validated['excluded-features']),
                'baseDiscount' => $validated['baseDiscount'],
                'recommended' => $validated['recommended'],
                'is_basic' => $validated['is_basic'],

            ]);

            return redirect()
                ->route('plan-index')
                ->with('success', 'Plan created successfully!');
        } catch (Exception $e) {
            Log::error('Error creating plan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while saving the plan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            $plan = Plan::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                // 'included-features' => 'nullable|array',
                'included-features.*' => 'string',
                // 'excluded-features' => 'nullable|array',
                'excluded-features.*' => 'string',
                'baseDiscount' => 'required|numeric|min:0|max:99',
                'recommended' => 'required|in:yes,no',
                'is_basic' => 'required|in:yes,no',
            ]);

            $plan->update([
                'name' => $validated['name'],
                'included_features' => empty($validated['included-features']) ? null : json_encode($validated['included-features']),
                'excluded_features' => empty($validated['excluded-features']) ? null : json_encode($validated['excluded-features']),
                'baseDiscount' => $validated['baseDiscount'],
                'recommended' => $validated['recommended'],
                'is_basic' => $validated['is_basic'],
            ]);

            return redirect()
                ->route('plan-index')
                ->with('success', 'Plan updated successfully!');
        } catch (Exception $e) {
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

            $state = Plan::findOrFail($id);

            $state->delete();

            if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'State deleted successfully.'
            ]);
        }

            return redirect()
                ->route('plan-index')
                ->with('success', 'State deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting state: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred while deleting the state.');
        }
    }


    // public function addIncludeFeature(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'name' => 'required|string|max:255',
    //             'is_excluded' => 'nullable|boolean',
    //             'course_id' => 'nullable|integer',
    //             'plan_id' => 'nullable|integer',
    //         ]);

    //         $validated['is_excluded'] = isset($validated['is_excluded']) ? (bool)$validated['is_excluded'] : 0;

    //         $feature = Feature::create([
    //             'name' => $validated['name'],
    //             'description' => $validated['name'] ?? null,
    //             'is_excluded' => $validated['is_excluded'],
    //             'course_id' => $validated['course_id'] ?? null,
    //             'plan_id' => $validated['plan_id'] ?? null,
    //         ]);

    //         return redirect()
    //             ->route('plan-index')
    //             ->with('success', 'Plan feature created successfully!');
    //     } catch (\Exception $e) {
    //         \Log::error('Error creating plan feature: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->with('error', 'An unexpected error occurred while saving the plan feature.');
    //     }
    // }



public function addIncludeFeature(Request $request)
{
    try {
        DB::beginTransaction();

        // Validation
        $validated = $request->validate([
            'plan_id'   => 'required|exists:tbl_plans,id',
            'course_id' => 'required|exists:tbl_courses,id',

            'included_features' => 'nullable|array',
            'included_features.*' => 'nullable|string|max:255',

            'excluded_features' => 'nullable|array',
            'excluded_features.*' => 'nullable|string|max:255',
        ]);


        $included = collect($validated['included_features'] ?? [])
            ->filter()
            ->map(fn ($v) => trim($v))
            ->unique()
            ->values();

        $excluded = collect($validated['excluded_features'] ?? [])
            ->filter()
            ->map(fn ($v) => trim($v))
            ->unique()
            ->values();


        $intersection = $included->intersect($excluded);
        if ($intersection->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Same feature cannot be both included and excluded.',
                'errors' => [
                    'features' => $intersection->values(),
                ]
            ], 422);
        }


        Feature::where('plan_id', $validated['plan_id'])
            ->where('course_id', $validated['course_id'])
            ->delete();

        foreach ($included as $name) {
            Feature::create([
                'name'        => $name,
                'is_excluded' => 0,
                'plan_id'     => $validated['plan_id'],
                'course_id'   => $validated['course_id'],
            ]);
        }


        foreach ($excluded as $name) {
            Feature::create([
                'name'        => $name,
                'is_excluded' => 1,
                'plan_id'     => $validated['plan_id'],
                'course_id'   => $validated['course_id'],
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Features saved successfully!',
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error saving plan features', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while saving features.',
        ], 500);
    }
}






}
