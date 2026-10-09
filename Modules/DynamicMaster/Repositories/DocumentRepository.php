<?php
namespace Modules\DynamicMaster\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentRepository
{
    public function uploadAttachment($request)
    {
        try {
            if ($request->hasFile('attachment_fileee')) {
                $files = $request->file('attachment_fileee');
                $source_id = intVal($request->source_id) == 0
                    ? decrypt($request->source_id)
                    : $request->source_id;

                foreach ($files as $file) {
                    $image_path = Controller::helper('CommonHelper')::folder_name(
                        $source_id,
                        $request->source,
                        'Internal Document',
                        $file,
                        10,
                        'image_document',
                        $request->module_name
                    );

                    if (empty($image_path)) {
                        DB::rollback();
                        return [
                            'status' => false,
                            'message' => __('common.folder-name-not-generating'),
                        ];
                    }

                    $model = Controller::model('GlobalAttachement', 'DynamicMaster');
                    $img = new $model();
                    $img->source_id = $source_id;
                    $img->source = $request->source;
                    $img->attachment_name = $image_path[1];
                    $img->attachment_type = $file->getClientOriginalExtension();
                    $img->description = $request->description;
                    $img->created_by = Auth::user()->id;
                    $img->status = "Draft";
                    $img->save();
                }

                addToLog($request->source, $source_id, ' has uploaded documents');
                return response()->json(['success' => true, 'message' => __('common.file-uploaded-successfully')]);
            }

            return response()->json(['error' => false, 'message' => __('common.file-not-found')], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAttachmentList($request)
    {
        try {
            $source_id = intVal($request->source_id) == 0
                ? decrypt($request->source_id)
                : $request->source_id;

            $model = Controller::model('GlobalAttachement', 'DynamicMaster');

            $data = $model::where('created_by', Auth::user()->id)
                ->where('source_id', $source_id)
                ->where('source', $request->source)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json(['data' => $data, 'message' => 'File List']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getSubmittedAttachments($request)
    {
        try {
            $source_id = intVal($request->source_id) == 0
                ? decrypt($request->source_id)
                : $request->source_id;

            $model = Controller::model('GlobalAttachement', 'DynamicMaster');

            $data = $model::where('created_by', Auth::user()->id)
                ->where('source_id', $source_id)
                ->where('source', $request->source)
                ->where('status', 'Submitted')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json(['data' => $data, 'message' => 'Submitted File List']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // public function deleteAttachment($request)
    // {
    //     try {
    //         $document_id = intVal($request->document_id) == 0
    //             ? convert_uudecode(base64_decode($request->document_id))
    //             : $request->document_id;

    //         $model = Controller::model('GlobalAttachement', 'DynamicMaster');
    //         $data = $model::where('id', $document_id)->first();

    //         $model::where('id', $document_id)
    //             ->where('status', 'Draft')
    //             ->delete();

    //         addToLog($data->source, $data->source_id, ' has deleted documents');

    //         return response()->json(['data' => $document_id, 'message' => __('common.file-deleted-successfully')]);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => __('common.file-delete-failed') . $e->getMessage()], 500);
    //     }
    // }


    public function deleteAttachment($request)
    {
        try {
            $document_id = intVal($request->document_id) == 0
                ? decrypt($request->document_id)
                : $request->document_id;

            $model = Controller::model('GlobalAttachement', 'DynamicMaster');
            $data = $model::where('id', $document_id)->first();

            if (!$data) {
                return response()->json(['success' => false, 'message' => __('common.file-not-found')], 404);
            }
            if (!empty($data->attachment_name) && Storage::disk('public')->exists($data->attachment_name)) {
                Storage::disk('public')->delete($data->attachment_name);
            }
            $model::where('id', $document_id)
                ->where('status', 'Draft')
                ->delete();
            addToLog($data->source, $data->source_id, ' has deleted documents');

            return response()->json(['data' => $document_id, 'message' => __('common.file-deleted-successfully')]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('common.file-delete-failed') . $e->getMessage()
            ], 500);
        }
    }


    public function submitAttachment($request)
    {
        try {
            $document_id = intVal($request->document_id) == 0
                ? decrypt($request->document_id)
                : $request->document_id;

            $model = Controller::model('GlobalAttachement', 'DynamicMaster');

            $model::where('id', $document_id)
                ->where('status', 'Draft')
                ->update(['status' => 'Submitted']);

            $data = $model::where('id', $document_id)->first();

            addToLog($data->source, $data->source_id, ' has submitted documents');

            return response()->json(['data' => $document_id, 'message' => __('common.file-submitted-successfully')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => __('common.file-submitted-failed') . $e->getMessage()], 500);
        }
    }
}
