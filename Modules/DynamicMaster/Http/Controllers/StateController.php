<?php
namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Modules\DynamicMaster\Models\State;
use Yajra\DataTables\DataTables;

class StateController extends Controller
{

    public function index()
    {
        return view('dynamicmaster::State.index');

    }

    public function getStateData()
    {
        $states = State::query();

        return DataTables::of($states)
            ->addColumn('actions', function ($row) {
                $encryptedId = encrypt($row->id);
                $viewUrl = route('State.view', ['id' => $encryptedId]);
                $editUrl = route('State.edit', ['id' => $encryptedId]);
                $deleteUrl = route('State.delete', ['id' => $encryptedId]);

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
        $countries = \Modules\DynamicMaster\Models\Country::get();
        return view('dynamicmaster::State.add', compact('countries'));
    }

    public function viewOrEdit(Request $request, $id)
    {
        if ($request?->is('*state/view/*')) {
            $flag = 'View';
        } else if ($request?->is('*state/edit/*')) {
            $flag = 'Edit';
        }
        $id = decrypt($id);
        $state = State::findOrFail($id);
        $countries = \Modules\DynamicMaster\Models\Country::get();

        return $flag ? view('dynamicmaster::State.view', compact('state', 'countries', 'flag')) : abort(404, 'Page Not found');

    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'url' => 'required|url|max:255',
                'country_id' => 'required|exists:tbl_country,id',
                'iso2' => 'nullable|string|max:6',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'wikiDataId' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the highlighted errors.' . $validator?->messages() . '');
            }

            State::create($validator->validated());

            return redirect()
                ->route('state.create')
                ->with('success', 'State created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating state: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while saving the state.');
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            $state = State::findOrFail($id);

            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'url' => 'required|string|max:255',
                'country_id' => 'required|exists:tbl_country,id',
                'iso2' => 'nullable|string|max:6',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'wikiDataId' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the highlighted errors.');
            }

            $state->update($validator->validated());

            return redirect()
                ->route('state.edit', encrypt($state->id))
                ->with('success', 'State updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating state: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while updating the state.');
        }
    }

    public function destroy($id)
    {
        try {
            $id = decrypt($id);

            $state = State::findOrFail($id);

            $state->delete();

            return redirect()
                ->route('state.index')
                ->with('success', 'State deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting state: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred while deleting the state.');
        }
    }







}
