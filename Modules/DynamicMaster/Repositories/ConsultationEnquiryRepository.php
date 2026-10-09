<?php

namespace Modules\DynamicMaster\Repositories;
use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Models\ConsultationEnquiry;
use Illuminate\Support\Facades\DB;

class ConsultationEnquiryRepository extends Controller
{
    protected $model;

    public function __construct(ConsultationEnquiry $model)
    {
        $this->model = $model;
    }

    public function EnquiriesList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('ConsultationEnquiry', 'DynamicMaster')::with('CourseCountryName')->orderby('id', 'DESC');
        }

    public static function EnquiriesFilters($query, $request)
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

    public function EnquiriesStatusUpdate($id)
    {
        $model = Controller::model('ConsultationEnquiry', 'DynamicMaster');

        try {
            if (!$id) {
                return ['status' => false, 'message' => 'ID is required.'];
            }

            $entry = $model::find($id);
            if (!$entry) {
                return ['status' => false, 'message' => 'Submission not found.'];
            }

            $entry->is_contacted = "Contacted";
            $entry->save();

            // addToLog('ContactSubmission', 'Updated status to Contacted for ID: ' . $id);

            return [
                'status' => true,
                'message' => 'Enquiry status updated successfully!',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Enquiries Status Update");
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
