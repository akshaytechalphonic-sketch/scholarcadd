<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use log;

use Carbon\Carbon;

class NumberSequenceHelpers
{
    public static function applysequence_regitration($type)
    {
        $map_id = Controller::model('NumberSequenceMap', 'NumberSequence')::where('name', $type)->pluck('id')->first();
        if ($map_id) {
            $sequence = Controller::model('NumberSequence', 'NumberSequence')::where('numbersequence_map_id', $map_id)->first();
            if ($sequence) {
                return $sequence?->constant_no . str_pad($sequence?->next, $sequence?->range_no, '0', STR_PAD_LEFT);
            }
            return 0;
        }
        return 0;
    }

    public static function apply_no_sequence($type, $company_id)
    {
        //dd($type,$company_id);
        $cmp_id = Controller::helper('CommonHelper')::sequence_type();
        if ($cmp_id) {
            $company_id = $cmp_id;
        }

         
        $map_id = Controller::model('NumberSequenceMap', 'NumberSequence')::where('name', $type)->pluck('id')->first();
        if ($map_id) {
            $sequence = Controller::model('NumberSequence', 'NumberSequence')::where('numbersequence_map_id', $map_id)->where('company_id', $company_id)->first();
            // dd($sequence);
            if ($sequence) {
                return $sequence?->constant_no . str_pad($sequence?->next, $sequence?->range_no, '0', STR_PAD_LEFT);
            }
            return 0;
        }
        return 0;
    }

    public static function update_no_sequence($type, $company_id)
    {
        $cmp_id = Controller::helper('CommonHelper')::sequence_type();
        if ($cmp_id) {
            $company_id = $cmp_id;
        }

        if ($type == "Supplier Request" || $type == "Vendor Registration" || $type == "Vendor Approval") {
            $company_id = Controller::model('Company', 'DynamicMaster')::where('default_company', 'Yes')->pluck('id')->first();
            if (!$company_id) {
                $company_id = 1;
            }
        }

        $map_id = Controller::model('NumberSequenceMap', 'NumberSequence')::where('name', $type)->pluck('id')->first();
        if ($map_id) {
            $sequence = Controller::model('NumberSequence', 'NumberSequence')::where('numbersequence_map_id', $map_id)->where('company_id', $company_id)->first();
            if ($sequence) {
                $sequence->next = intVal($sequence->next) + 1;
                $sequence->save();
                return true;
            }
            return false;
        }
        return false;
    }


    public static function generateNumberSequence($model, $column, $prefix, $lengthSequence, $module = null)
    {
        if ($module) {
            $mod = 'Modules\\' . $module . '\\Models\\' . $model;
        } else {
            $mod = 'App\Models\\' . $model;
        }

        $lastSerialNumber = $mod::latest()->first();
        if ($lastSerialNumber) {
            $lastSerialNumberWithoutPrefix = (int) substr($lastSerialNumber->$column, strlen($prefix));
            $nextSerialNumber = $prefix . str_pad($lastSerialNumberWithoutPrefix + 1, $lengthSequence, '0', STR_PAD_LEFT);
        } else {
            $nextSerialNumber = $prefix . str_pad(1, $lengthSequence, '0', STR_PAD_LEFT);
        }
        return $nextSerialNumber;
    }
}
