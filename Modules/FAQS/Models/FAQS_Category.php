<?php

namespace Modules\FAQS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQS_Category extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_faqs_category';

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
    public function updator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'updated_by');
    }

    public function company()
    {
        $userModel = get_class(get_model_instance('Company', 'DynamicMaster'));
        return $this->belongsTo($userModel, 'company_id');
    }
}
