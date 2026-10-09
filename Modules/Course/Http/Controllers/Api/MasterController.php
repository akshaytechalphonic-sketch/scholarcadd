<?php

namespace Modules\Course\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\ApiResponseTrait;
use Modules\Course\Models\CourseCountry;
use Modules\DynamicMaster\Models\State;

class MasterController extends Controller
{
    use ApiResponseTrait;

    public function parent_list(Request $request)
    {
        try {
            $type = $request->master_name;
            if ($type) {
                $all_data = masterParentData($type);
                $data = [];
                foreach ($all_data as $line) {
                    $data[] = [
                        'id' => encrypt($line?->id) ?? '',
                        'name' => $line?->name ?? '',
                        'code' => $line?->code ?? '',
                        'description' => $line?->description ?? '',
                    ];
                }
                return $this->successResponse($data, 'Data fetched successfully');
            } else {
                return $this->errorResponse('master name is required', 403);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function parent_show(Request $request)
    {
        try {
            $id = $request->id ?? $request->input('id') ?? ($request->isJson() ? $request->json('id') : null) ?? ($request->all()['id'] ?? null);
            if ($id) {
                $dat = self::model('MasterParent', 'DynamicMaster')::find((function() use ($id) { try { return decrypt($id); } catch (\Exception $e) { return $id; } })());
                if (!$dat) {
                    return $this->errorResponse('No Data Found', 404);
                }
                $data = [
                    'id' => encrypt($dat?->id) ?? '',
                    'name' => $dat?->name ?? '',
                    'code' => $dat?->code ?? '',
                    'description' => $dat?->description ?? '',
                ];

                return $this->successResponse($data, 'Data fetched successfully');
            } else {
                return $this->errorResponse('Id is required', 403);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }











    public function country_list()
    {
        try {
            $all_data = self::model('CourseCountry', 'Course')::where('status', 'Active')->get();
            if ($all_data->isEmpty()) {
                return $this->errorResponse('No Data Found', 404);
            }
            $data = [];
            foreach ($all_data as $line) {
                $data[] = [
                    'id' => $line?->id ?? '',
                    'name' => $line?->name ?? '',
                    'url' => $line?->url ?? '',
                    'numeric_code' => $line?->numeric_code ?? '',
                    'iso2' => $line?->iso2 ?? '',
                    'phonecode' => $line?->phonecode ?? '',
                    'currency' => $line?->currency ?? '',
                    'currency_name' => $line?->currency_name ?? '',
                    'currency_symbol' => $line?->currency_symbol ?? '',
                ];
            }
            return $this->successResponse($data, 'Data fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function country_show(Request $request)
    {
        try {
            $id = $request->id ?? $request->input('id') ?? ($request->isJson() ? $request->json('id') : null) ?? ($request->all()['id'] ?? null);
            if ($id) {
                $dat = self::model('CourseCountry', 'Course')::find((function() use ($id) { try { return decrypt($id); } catch (\Exception $e) { return $id; } })());
                if (!$dat) {
                    return $this->errorResponse('No Data Found', 404);
                }

                $data = [
                    'id' => encrypt($dat?->id) ?? '',
                    'name' => $dat?->name ?? '',
                    'url' => $dat?->url ?? '',
                    'isPage' => $dat?->isPage ?? '',
                    'iso3' => $dat?->iso3 ?? '',
                    'numeric_code' => $dat?->numeric_code ?? '',
                    'iso2' => $dat?->iso2 ?? '',
                    'phonecode' => $dat?->phonecode ?? '',
                    'capital' => $dat?->capital ?? '',
                    'currency' => $dat?->currency ?? '',
                    'value_in_dollar' => $dat?->value_in_dollar ?? '',
                    'currency_name' => $dat?->currency_name ?? '',
                    'currency_symbol' => $dat?->currency_symbol ?? '',
                    'tld' => $dat?->tld ?? '',
                    'native' => $dat?->native ?? '',
                    'region' => $dat?->region ?? '',
                    'subregion' => $dat?->subregion ?? '',
                    'timezones' => $dat?->timezones ?? '',
                    'translations' => $dat?->translations ?? '',
                    'latitude' => $dat?->latitude ?? '',
                    'longitude' => $dat?->longitude ?? '',
                    'emoji' => $dat?->emoji ?? '',
                    'emojiU' => $dat?->emojiU ?? '',
                    'flag' => $dat?->flag ?? '',
                    'wikiDataId' => $dat?->wikiDataId ?? '',
                ];

                return $this->successResponse($data, 'Data fetched successfully');
            } else {
                return $this->errorResponse('Id is required', 403);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function state_list(Request $request)
    {
        try {
            $country_id = $request->country_id ?? $request->input('country_id') ?? ($request->isJson() ? $request->json('country_id') : null) ?? ($request->all()['country_id'] ?? null);
            $country_id = CourseCountry::where('iso2', $country_id)->orWhere('id', $country_id)->first()?->id;

            $all_data = self::model('State', 'Course')::where('status', 'Active')
                ->when($country_id, function ($query) use ($country_id) {
                    return $query->where('country_id', $country_id);
                })
                ->get();
            if ($all_data->isEmpty()) {
                return $this->errorResponse('No Data Found', 404);
            }
            $data = [];
            foreach ($all_data as $line) {
                $data[] = [
                    'id' => encrypt($line?->id) ?? '',
                    'name' => $line?->name ?? '',
                    'url' => $line?->url ?? '',
                    'country' => $line?->country?->name ?? '',
                    'country_code' => $line?->name ?? '',
                    'iso2' => $line?->iso2 ?? '',
                ];
            }
            return $this->successResponse($data, 'Data fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function state_show(Request $request)
    {
        try {
            $id = $request->id ?? $request->input('id') ?? ($request->isJson() ? $request->json('id') : null) ?? ($request->all()['id'] ?? null);
            if ($id) {
                $dat = self::model('State', 'Course')::find((function() use ($id) { try { return decrypt($id); } catch (\Exception $e) { return $id; } })());
                if (!$dat) {
                    return $this->errorResponse('No Data Found', 404);
                }

                $data = [
                    'id' => encrypt($dat?->id) ?? '',
                    'name' => $dat?->name ?? '',
                    'url' => $dat?->url ?? '',
                    'isPage' => $dat?->isPage ?? '',
                    'country' => $dat?->country?->name ?? '',
                    'country_code' => $dat?->country_code ?? '',
                    'fips_code' => $dat?->fips_code ?? '',
                    'iso2' => $dat?->iso2 ?? '',
                    'type' => $dat?->type ?? '',
                    'latitude' => $dat?->latitude ?? '',
                    'longitude' => $dat?->longitude ?? '',
                    'flag' => $dat?->flag ?? '',
                    'wikiDataId' => $dat?->wikiDataId ?? '',
                ];

                return $this->successResponse($data, 'Data fetched successfully');
            } else {
                return $this->errorResponse('Id is required', 403);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function city_list(Request $request)
    {
        try {
            $country_id = $request->country_id ?? $request->input('country_id') ?? ($request->isJson() ? $request->json('country_id') : null) ?? ($request->all()['country_id'] ?? null);
            $state_id = $request->state_id ?? $request->input('state_id') ?? ($request->isJson() ? $request->json('state_id') : null) ?? ($request->all()['state_id'] ?? null);
            // if($country_id){
            //     $country_id = decrypt($country_id);
            // }
            // if($state_id){
            //     $state_id = decrypt($state_id);
            // }
            $country_id = CourseCountry::where('iso2', $country_id)->orWhere('id', $country_id)->first()?->id;
            $state_id = State::where('name', $state_id)->first()?->id;

             $all_data = self::model('City', 'Course')::where('status', 'Active')
                ->when($country_id, function ($query) use ($country_id) { return $query->where('country_id', $country_id);                })
                ->when($state_id, function ($query) use ($state_id) { return $query->where('state_id', $state_id);
                })
                // ->when(is_null($state_id), function ($query) {
                //     return $query->whereRaw('1 = 0'); // forces empty result
                // })
                ->get();

            if ($all_data->isEmpty()) {
                return $this->errorResponse('No Data Found', 404);
            }
            $data = [];
            foreach ($all_data as $line) {
                $data[] = [
                    'id' => encrypt($line?->id) ?? '',
                    'name' => $line?->name ?? '',
                    'url' => $line?->url ?? '',
                    'state' => $line?->state?->name ?? '',
                    'state_code' => $line?->state_code ?? '',
                    'country' => $line?->country?->name ?? '',
                    'country_code' => $line?->name ?? '',
                ];
            }
            return $this->successResponse($data, 'Data fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function city_show(Request $request)
    {
        try {
            $id = $request->id ?? $request->input('id') ?? ($request->isJson() ? $request->json('id') : null) ?? ($request->all()['id'] ?? null);
            if ($id) {
                $dat = self::model('City', 'Course')::find((function() use ($id) { try { return decrypt($id); } catch (\Exception $e) { return $id; } })());
                if (!$dat) {
                    return $this->errorResponse('No Data Found', 404);
                }

                $data = [
                    'id' => encrypt($dat?->id) ?? '',
                    'name' => $dat?->name ?? '',
                    'url' => $dat?->url ?? '',
                    'isPage' => $dat?->isPage ?? '',
                    'state' => $dat?->state?->name ?? '',
                    'state_code' => $dat?->state_code ?? '',
                    'country' => $dat?->country?->name ?? '',
                    'country_code' => $dat?->country_code ?? '',
                    'latitude' => $dat?->latitude ?? '',
                    'longitude' => $dat?->longitude ?? '',
                    'flag' => $dat?->flag ?? '',
                    'wikiDataId' => $dat?->wikiDataId ?? '',
                ];

                return $this->successResponse($data, 'Data fetched successfully');
            } else {
                return $this->errorResponse('Id is required', 403);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }



}
