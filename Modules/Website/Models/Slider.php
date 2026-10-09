<?php

namespace Modules\Website\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $guarded = [' '];
    protected $table= 'tbl_slider';
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
        $userModel = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($userModel, 'company_id');
    }
}
