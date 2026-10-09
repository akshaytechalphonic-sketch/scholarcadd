<?php

namespace  Modules\Workflow\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class WorkflowApplyRepository
{
    public function apply_workflow($document,$doc_id,$source=null)
    { 
        try{
        Controller::db()::beginTransaction();
        $doc_model=null;
            $id = decrypt($doc_id);
            switch ($document) {
                case 'Purchase Request':
                    $model_name = 'PurchaseRequest';
                    $module = 'PurchaseRequest';
                    break;
                case 'Quotation':
                    $model_name = 'Quotation';
                    $module = 'Quotation';
                    break;
                case 'Purchase Order':
                    $model_name = 'PurchaseOrder';
                    $module = 'PurchaseOrder';
                    break;
                case 'Delivery Note':
                    $model_name = 'DeliveryNoteHeader';
                    $module = 'PurchaseOrder';
                    break;
                case 'Invoice':
                    $model_name = 'InvoiceHeader';
                    $module = 'PurchaseOrder';
                    break;
                case 'Vendor Request':
                    $model_name = 'Vendor';
                    $module = 'Vendor';
                    break;

                case 'Customer Request':
                    $model_name = 'Customer';
                    $module = 'Customer';
                    break;
 

                default:
                    $model_name = 'Contracts';
                    $module = 'Vendor';
                    break;
            }
            switch ($source) {
                case 'Gl Voucher':
                    $model_name = 'GLEntryHeader';
                    $module = 'Financial';
                    break;
                case 'APInvoice':
                    $model_name = 'ApInvoiceHeader';
                    $module = 'Financial';
                    break;

                case 'APPayment':
                    $model_name = 'ApPaymentHeader';
                    $module = 'Financial';
                    break;

                 case 'ARInvoice':
                    $model_name = 'ArInvoiceHeader';
                    $module = 'Financial';
                    break;

                case 'ARPayment':
                    $model_name = 'ArPaymentHeader';
                    $module = 'Financial';
                    break;
            }

            $data = Controller::model($model_name,$module)::find($id);            
            $status_array = ['Completed','Draft','Created'];
            if(!in_array($data?->status,$status_array)){
                Controller::db()::rollback();
                return "Document is not in mode to sent to workflow";
            }

            $message = $document.' submitted to workflow for approval - '.$data->code;
            addToLog($model_name,$data->id,$message);

            $source = $source ? $source : $document;

            $cmp_and_user = Controller::loginandcompany();
            if($document == "GRN" || $document == "Invoice"){
                $return_val = Controller::helper('ApplyWorkflowHelper')::get_workflow_apply_on($document, $id, $data->code,$data?->company_id,$source);
            }
            else{
                $return_val = Controller::helper('ApplyWorkflowHelper')::get_workflow_apply_on($document, $id, $data->code,$cmp_and_user[1],$source);
            }
            if($return_val == "Success" && ($data->status == "Draft" || $data->status == "Created")){

                $data->status = "In review";
                $data->save();

                if($document == "Delivery Note"){
                    $data = Controller::model('DeliveryNoteHeader','PurchaseOrder')::with('delivery_note_line','delivery_note_line.purchase_order_line')->find($id);
                    foreach($data?->delivery_note_line as $gline){                        
                        $po_line = $gline?->purchase_order_line;
                        $po_line->grn_rem_quantity =  $po_line->grn_rem_quantity-$gline->quantity;
                        $po_line->grn_net_amount = $po_line->grn_net_amount - $gline->net_amount;
                        $po_line->save();
                    }
                    $message = 'submitted grn for purchaseorder '.$data?->purchaseorder?->code .' grn code '.$data->code ;
                    addToLog('PurchaseOrder',$data->purchaseorder_id,$message);                    
                }

                if($document == "Invoice"){
                    $inv_line = Controller::helper('ModelHelper')::children_by_parent('InvoiceLine','invoiceheader_id',$id);
                    foreach($inv_line as $inline){
                        $po_line = Controller::model('PurchaseOrderLine')::find($inline->purchaseorderline_id);
                        $po_line->inv_rem_quantity =  $po_line->inv_rem_quantity-$inline->quantity;
                        $po_line->inv_net_amount = $po_line->inv_net_amount - $inline->net_amount;
                        $po_line->save();
                    }
                    $message = 'submitted invoice for purchaseorder '.$data?->purchaseorder?->code .' invoice code '.$data->code ;
                    addToLog('PurchaseOrder',$data->purchaseorder_id,$message);
                }
              
                Controller::db()::commit();
                return $return_val;

            }
            else{
                Controller::db()::rollback();
                return $return_val;
            }

        }
        catch(\Exception $e){
            Controller::db()::rollback();
            return $e->getMessage();
        }
    }

}

