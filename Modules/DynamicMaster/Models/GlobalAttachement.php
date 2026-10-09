<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalAttachement extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_attachements';

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
}
