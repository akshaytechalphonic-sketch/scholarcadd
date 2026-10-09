<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

class BaseRepository extends Controller
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model;
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

     public function masteryajraList($type)
    {
        dd('hello');
        $cmp_id = self::user()->company_id;
        $type_id = self::model('MasterType','DynamicMaster')::where('name',$type)->pluck('id')->first();
        dd($type_id);
        $data = self::model('MasterParent','DynamicMaster')::ActiveOnly($cmp_id)
        ->where('master_type_id',$type_id)
        ->orderby('id','desc');
        dd($data);
        return $data;

    }


}
