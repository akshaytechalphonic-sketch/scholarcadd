<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessgroupController extends Controller
{
      public function index()
     {
        return view('website::business-group-coaching.index');

     }  
   public function create(Request $request)
     {
       $data = null;
        return view('website::business-group-coaching.create', compact('data'));

     }   
}
