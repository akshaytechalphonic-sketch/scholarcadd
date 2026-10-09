<?php
namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $table = "tbl_state";
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
