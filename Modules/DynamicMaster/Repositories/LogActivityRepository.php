<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LogActivityRepository
{
    public function all($request)
    {
         try{
            $source_id = decrypt($request->source_id);
            $user = Auth::user();
            $data = Controller::model('ActivityLog','DynamicMaster')::
            join('users','users.id','tbl_activity_logs.created_by')
            ->select('tbl_activity_logs.*','users.name as created_by_name')
            ->where('tbl_activity_logs.source',$request->source)
            ->where('tbl_activity_logs.source_id',$source_id)
            ->orderby('tbl_activity_logs.id','Desc')
            ->get();
            return ['data'=>$data,'message'=>'common.list'];
        }
        catch (Exception $e) {
            return ['data' =>null, 'message' => $e->getMessage()];
        }
    }

    public function comment_list($request)
    {
        $cmp_and_user = Controller::loginandcompany();
         $user = $cmp_and_user[0];
         $source_id= decrypt($request->source_id);
        $data = Controller::model('Comment','DynamicMaster')::with(['CommentImage','creator'])
        ->where('source',$request->source)
        ->where('source_id',$source_id)
        ->orderby('id','Desc')
        ->get();
        return ['data'=>$data,'message'=>'common.list'];

    }

    public function comment_store($request)
    {
         try {
             $source_id= decrypt($request->source_id);
             $cmp_and_user = Controller::loginandcompany();
            $mod_comment = Controller::model('Comment','DynamicMaster');
            $comment = new $mod_comment();
            $comment->source= $request->source;
            $comment->source_id = $source_id;
            $comment->comment = $request->comment;
            $comment->created_by = $cmp_and_user[0];
            $comment->save();
            $comment_id = $comment->id;

            if ($request->hasFile('attachment_file')) {
                $files = $request->file('attachment_file');
                foreach ($files as $file) {
                    $image = Controller::helper('CommonHelper')::folder_name($cmp_and_user[1], 'Comment', 'Comment', $file, 10, 'image_document','DynamicMaster');
                    if (empty($image)) {
                        Controller::db()::rollback();
                        return [
                            'status' => false,
                            'message' => __('common.folder-name-not-generating'),
                        ];
                    }
                    $mod = Controller::model('CommentImage','DynamicMaster');
                    $imgs = new $mod();
                    $imgs->comment_id = $comment_id;
                    $imgs->attachment_name = $image[1];
                    $imgs->attachment_type = $file->getClientOriginalExtension();
                    $imgs->created_by = $cmp_and_user[0];
                    $imgs->save();
                }
            }

            $message = ' has added comments';
            addToLog($request->source,$source_id,$message);
            return ['data' => $source_id, 'message' => __('common.comment-added-successfully')];

        } catch (\Exception $e) {
            return ['data' => null, 'message' => $e->getMessage()];
        }

    }

}

