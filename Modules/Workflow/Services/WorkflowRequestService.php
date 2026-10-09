<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\WorkflowRequestRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class WorkflowRequestService extends Controller
{
    protected WorkflowRequestRepository $repository;

    public function __construct(WorkflowRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {

                $query = $this->repository->yajraList()
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='created_by',function ($query) use ($request){
                    $query->where($request->custom_filter[0], $request->custom_filter[1]);
                })

                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='created_by',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('user', function ($subQuery) use ($item) {
                        $subQuery->where('name', $item);
                    });
                })
                ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',function ($query) use ($request) {
                        $start_date = $request->custom_filter[1];
                        $end_date = $request->custom_filter[2];
                        $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                    }
                );

            return DataTables::of($query)

            ->addColumn('requester', function ($row) {
                return Controller::helper('WorkflowHelper')::doument_requester_byrequester_id($row?->requester_id);
            })
            ->addColumn('status', function ($row) {
                return status_check($row?->status);
            })
            ->addColumn('type', function ($row) {
                return $row?->mapName?->workflow_name;
            })
            ->addColumn('date', function ($row) {
                return date_formate($row?->assign_date);
            })

            ->addColumn('workflow', function ($row) {
                return $row?->head_data?->source;
            })
            ->addColumn('actions', function ($row) {
                $encr_id = encrypt($row?->id);
                $encr_id = "'$encr_id'";
                $approve_type = "'approve-sidebar'";
                $reject_type = "'reject-sidebar'";
                $chng_req_type = "'change-request_modal-sidebar'";
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                    $dropdown.= '<li> <a href="'.$row?->url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class=" ri-eye-line"></i> View</a> </li>';
                    if($row?->status == "Pending"){
                        $dropdown.= '<li> <button onclick="view_model('.$approve_type.',' . $encr_id . ')" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-check-line mr-2"></i> Approve </button></li> ';
                        $dropdown.= '<li> <button onclick="view_model('.$reject_type.',' . $encr_id . ')" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-close-line mr-2"></i> Reject </button></li> ';
                        $dropdown.= '<li> <button onclick="view_model('.$chng_req_type.',' . $encr_id . ')" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-list-check mr-2"></i> Change Request </button></li> ';
                    }
                    $dropdown.= '</ul> </div> </div>';
                return $dropdown;
            })
                ->rawColumns(['actions','status'])
            ->make(true);
            }
            return view('workflow::workflow_request.index');

        } catch (\Exception $e) {
            catch_log($e,"Getting list of number sequence data");
            if ($request->ajax()) {
                   return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function submitApproveRejModal(Request $request)
    {
        try{
            Controller::db()::beginTransaction();
            $date = Controller::helper('CommonHelper')::current_date();
            $cmp_and_user = Controller::loginandcompany();

            $trline_id = decrypt($request->request_id);

            $source = Controller::helper('WorkflowHelper')::transition_current_status($request->request_id);
            if($source){
                $return_value = $this->repository->approve_Workflow($request->request_id, $request->status, $request->comment);
                $status = $return_value[0]; //'Approved';
                $type = $return_value[1]; //'Document Request';
                $ref_id = $return_value[2];
                $w_id = convert_uudecode(base64_decode($request->request_id));
                $curr_line = Controller::model('Workflow_TransitionLine','Workflow')::find($w_id);
                $req_id = $curr_line?->ref_code;
                $company = Controller::helper('EmailHelper')::company_name();

                if ($return_value) {
                    if ($status == 'Assigned') {
                        Controller::db()::commit();
                        return [true,$status];
                    }

                    if($type == "Vendor Request"){
                        $model = 'Vendor';
                        $module = "Vendor";
                        $vendor_profile = Controller::model($model,$module)::find($ref_id);
                        if ($status == 'Approved'){
                            $vendor_profile->status = $status;
                            $user = Controller::model('User')::where('email',$vendor_profile->email)->first();
                            $random = rand(10000000,99999999);
                            if(!$user){
                                $user = Controller::helper('CommonHelper')::create_user($vendor_profile?->name,$vendor_profile?->email,null,$random,'Supplier',null);
                            }
                            $user->type = "Approved Vendor";
                            $user->status = "Active";
                            $user->password = $random;
                            // $user->assignRole('Vendor');
                            $user->save();
                            $vendor_profile->save();

                            $approval_id = Controller::helper('NumberSequenceHelpers')::apply_no_sequence('Vendor Approval', $cmp_and_user[1]);
                            if ($approval_id == 0 || $approval_id == 1) {
                                Controller::db()::rollback();

                                return [false,"Vendor Approval number sequence not mapped"];
                            }

                            $email_teplate = masterParentSingleData('EmailMaster','name','Vendor Account Approval');
                            $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$vendor_profile?->name,$company,$vendor_profile?->code,$approval_id,$user->email,$random]);
                            if(!$mail[0]){
                                return [false,$mail[1]];
                            }
                            Controller::helper('EmailHelper')::mail_send($vendor_profile?->email,$mail[0],$mail[1],null,null,null,true);

                        }
                        else{
                            if($status == 'Rejected'){
                                $vendor_profile->status = $status;
                                $after_days = 30;

                                $email_teplate = masterParentSingleData('EmailMaster','name','Vendor Account Rejection');
                                $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$vendor_profile?->name,$company,$vendor_profile?->code,$after_days]);
                                Controller::helper('EmailHelper')::mail_send($vendor_profile?->email,$mail[0],$mail[1],null,null,null,true);
                            }
                            else if($status == 'Change Request'){
                                $vendor_profile->status = "Draft";
                                $vendor_profile->save();
                                $email_teplate = masterParentSingleData('EmailMaster','name','Vendor Account Changed Request');
                                $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$vendor_profile?->name,$company,$vendor_profile?->code,$request->comment]);

                                Controller::helper('EmailHelper')::mail_send($vendor_profile?->email,$mail[0],$mail[1],null,null,null,true);
                            }
                        $vendor_profile->save();
                        }
                    }

                    if ($type == 'Delivery Note'){
                        
                        $model = "DeliveryNoteHeader";
                        $module = "PurchaseOrder";
                        $url = route('delivery-note-list');
                        $grn = Controller::model($model,$module)::find($ref_id);
                        if($status == "Approved"){
                            //$email_teplate = masterParentSingleData('EmailMaster','name','GRN Approved');
                            // $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$grn?->purchaseorder?->code,$grn?->vendor?->name,$grn?->code,'GRN',$date,$url]);                            
                            // Controller::helper('EmailHelper')::mail_send($grn?->vendor?->email,$mail[0],$mail[1]);
                        }
                        elseif($status == "Rejected" || $status == "Change Request"){

                            $data = Controller::model('DeliveryNoteHeader','PurchaseOrder')::with('delivery_note_line','delivery_note_line.purchase_order_line')->find($ref_id);                            
                            foreach($data?->delivery_note_line as $gline){                        
                                $po_line = $gline?->purchase_order_line;
                                $po_line->grn_rem_quantity =  $po_line->grn_rem_quantity+$gline->quantity;
                                $po_line->grn_net_amount = $po_line->grn_net_amount + $gline->net_amount;
                                $po_line->save();
                            } 
                            // $email_teplate = masterParentSingleData('EmailMaster','name','GRN Rejected');
                            // $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$grn?->purchaseorder?->code,$grn?->vendor?->name,$grn?->code,'GRN',$date,$url]);

                            //Controller::helper('EmailHelper')::mail_send($grn?->vendor?->email,$mail[0],$mail[1]);

                        } 
                    }

                    if ($type == 'Invoice'){
                        $model = "InvoiceHeader";
                        $url = route('invoice-list');
                        $invoice = Controller::helper('ModelHelper')::find($model,$ref_id);
                        $currency = $invoice?->currency?->code;
                        $total = $invoice->net_amount;
                        if($status == "Approved"){

                            $email_teplate = masterParentSingleData('EmailMaster','name','Invoice Approved');
                            $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$invoice?->purchaseorder?->code,$invoice?->vendor?->name,$total,$currency,$invoice?->code,'Invoice',$date,$url]);

                            Controller::helper('EmailHelper')::mail_send($invoice?->vendor?->email,$mail[0],$mail[1]);
                        }
                        elseif($status == "Rejected" || $status == "Change Request"){

                            $inv_line = Controller::helper('ModelHelper')::children_by_parent('InvoiceLine','invoiceheader_id',$ref_id);
                            foreach($inv_line as $inline){
                                $po_line = Controller::model('PurchaseOrderLine')::find($inline->purchaseorderline_id);
                                $po_line->inv_rem_quantity =  $po_line->inv_rem_quantity + $inline->quantity;
                                $po_line->inv_net_amount = $po_line->inv_net_amount + $inline->net_amount;
                                $po_line->save();
                            }

                            $email_teplate = masterParentSingleData('EmailMaster','name','Invoice Rejected');
                            $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,[$invoice?->purchaseorder?->code,$invoice?->vendor?->name,$invoice?->code,'Invoice',$date,$url]);

                            Controller::helper('EmailHelper')::mail_send($invoice?->vendor?->email,$mail[0],$mail[1]);
                        }
                    }

                    if ($type == 'Purchase Request'){
                        $model = "PurchaseRequest";
                        $module = "PurchaseRequest";
                    }
                     if ($type == 'Purchase Order'){
                        $model = "PurchaseOrder";
                        $module = "PurchaseOrder";
                    }
                    if ($type == 'Quotation'){
                        $model = "Quotation";
                        $module = "Quotation";
                    }
                    if ($type == 'Customer Request'){
                        $model = "Customer";
                        $module = "Customer";
                    }

                    if($source == 'Gl Voucher')
                    {
                        $model = "GLEntryHeader";
                        $module = "Financial";
                    }

                    if($source == 'APInvoice')
                    {
                        $model = "ApInvoiceHeader";
                        $module = "Financial";
                    }

                    if($source == 'APPayment')
                    {
                        $model = "ApPaymentHeader";
                        $module = "Financial";
                    }

                    if($source == 'ARInvoice')
                    {
                        $model = "ArInvoiceHeader";
                        $module = "Financial";
                    }

                    if($source == 'ARPayment')
                    {
                        $model = "ArPaymentHeader";
                        $module = "Financial";
                    }


                    $data = Controller::model($model,$module)::find($ref_id);

                    if ($status == 'Change Request') {
                        $data->status = "Draft";
                    } else {
                        if ($type == 'Auction' && $data->status == "Approved"){
                            $data->status = "Published";
                        }
                        else{
                            $data->status = $status;
                        }
                    }

                    $message = $type.' is '.$status;
                    if($request->comment){
                        $message.= ' with comment - '.$request->comment;
                    }
                    addToLog($model,$data->id,$message);
                    $data->save();

                    Controller::db()::commit();
                    return [true,$request->status];

                } else {
                    return [false,"Error"];
                }
            }
           Controller::db()::rollback();
            return [false,"Error"];
        }catch(Exception $e){
            Controller::db()::rollback();
            return [true,$e->getMessage()];
        }

    }



}
