<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_comments';

   public function CommentImage()
    {
        $userModel = get_class(get_model_instance('CommentImage','DynamicMaster'));
        return $this->hasMany($userModel, 'comment_id', 'id');
    }

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
}
