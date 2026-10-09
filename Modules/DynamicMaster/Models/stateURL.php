<?php
namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class StateURL extends Model
{

    protected $table = "country_state_course_url";
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
