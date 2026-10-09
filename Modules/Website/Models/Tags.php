<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_tags';

    
    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'Approved');
    }


    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
     public function knowledgecenter()
    {
        return $this->belongsToMany(KnowledgeCenter::class);
    }
}