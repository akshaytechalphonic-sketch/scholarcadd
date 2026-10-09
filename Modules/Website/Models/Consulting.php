<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consulting extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_consulting';

    protected $casts = [
        'pointer' => 'array',
        'advantages'=>'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->meta_title);
            }
        });

        static::updating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->meta_title);
            }
        });
    }


    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'Approved');
    }
     public function consulting()
    {
        return $this->hasMany(SubConsulting::class, 'consultingid','id');
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

}
