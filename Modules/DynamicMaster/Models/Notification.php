<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table ="tbl_notification";    
    protected $guarded = [];  
     

}
