<?php

namespace Modules\Course\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Modules\Course\Models\CourseCategory;
// use Modules\Course\Models\CourseCountry;
// use Modules\Course\Models\State;
// use Modules\Course\Models\City;
use App\Traits\ApiResponseTrait;
class CourseMasterController extends Controller
{
    use ApiResponseTrait;

    // public function country_list()
    // {
    //     try { 
    //         $all_data = self::model('CourseCountry','Course')::where('status','Active')->get();
    //         if($all_data->isEmpty()) {
    //             return $this->errorResponse('No Data Found', 404);
    //         }
    //         $data = [];
    //         foreach($all_data as $line){
    //             $data[] = [
    //                 'id'=>encrypt($line?->id) ?? '', 
    //                 'name' => $line?->name ?? '',
    //                 'url' => $line?->url ?? '', 
    //                 'numeric_code' => $line?->numeric_code ?? '',
    //                 'iso2' => $line?->iso2 ?? '',
    //                 'phonecode' => $line?->phonecode ?? '',
    //                 'currency' => $line?->currency ?? '',
    //                 'currency_name' => $line?->currency_name ?? '',
    //                 'currency_symbol' => $line?->currency_symbol ?? '',
    //             ];
    //         }
    //         return $this->successResponse($data,'Data fetched successfully');
    //     } catch (\Exception $e) {
    //         return $this->errorResponse('An unexpected error occurred',500,$e->getMessage());
    //     }
    // }

    // public function country_show(Request $request)
    // { 
    //     try {
    //         $id = $request->id;            
    //         if($id){
    //             $dat = self::model('CourseCountry','Course')::find(decrypt($id));
    //             if(!$dat){
    //                 return $this->errorResponse('No Data Found', 404);
    //             }

    //             $data = [
    //                 'id'=>encrypt($dat?->id) ?? '',                                            
    //                 'name' => $dat?->name ?? '', 
    //                 'url' => $dat?->url ?? '', 
    //                 'isPage' => $dat?->isPage ?? '', 
    //                 'iso3' => $dat?->iso3 ?? '', 
    //                 'numeric_code' => $dat?->numeric_code ?? '', 
    //                 'iso2' => $dat?->iso2 ?? '', 
    //                 'phonecode' => $dat?->phonecode ?? '', 
    //                 'capital' => $dat?->capital ?? '', 
    //                 'currency' => $dat?->currency ?? '', 
    //                 'value_in_dollar' => $dat?->value_in_dollar ?? '', 
    //                 'currency_name' => $dat?->currency_name ?? '', 
    //                 'currency_symbol' => $dat?->currency_symbol ?? '', 
    //                 'tld' => $dat?->tld ?? '', 
    //                 'native' => $dat?->native ?? '', 
    //                 'region' => $dat?->region ?? '', 
    //                 'subregion' => $dat?->subregion ?? '', 
    //                 'timezones' => $dat?->timezones ?? '', 
    //                 'translations' => $dat?->translations ?? '', 
    //                 'latitude' => $dat?->latitude ?? '', 
    //                 'longitude' => $dat?->longitude ?? '', 
    //                 'emoji' => $dat?->emoji ?? '', 
    //                 'emojiU' => $dat?->emojiU ?? '', 
    //                 'flag' => $dat?->flag ?? '', 
    //                 'wikiDataId' => $dat?->wikiDataId ?? '', 	 
    //             ];        
 
    //             return $this->successResponse($data, 'Data fetched successfully');
    //         }
    //         else{
    //              return $this->errorResponse('Id is required', 403);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
    //     }
    // }

 
 
}
