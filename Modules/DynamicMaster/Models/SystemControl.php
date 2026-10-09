<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemControl extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_system_control';

    public function period()
    {
        $glAccountModel = get_class(get_model_instance('GLPeriodHeader','Financial'));
        return $this->belongsTo($glAccountModel, 'default_period_id');
    }

}
