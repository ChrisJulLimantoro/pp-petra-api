<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    public function getAll($filterParams = [])
    {
        $this->model = $this->model->with($this->model->relations());

        if (!empty($filterParams)) {
            $this->model = $this->model->where($filterParams);
        }

        return $this->model->get();
    }

    public function getAllWithPagination($count, $filterParams = [])
    {
        $this->model = $this->model->with($this->model->relations());

        if (!empty($filterParams)) {
            $this->model = $this->model->where($filterParams);
        }

        return $this->model
            ->paginate($count)
            ->items();
    }

    public function getAllWithIlikeRuleParam($filterParams, $ilikeParams)
    {
        $this->model = $this->model->with($this->model->relations());

        if (!empty($ilikeParams)) {
            foreach ($ilikeParams as $ilikeParam) {
                if (array_key_exists($ilikeParam, $filterParams)) {
                    $this->model = $this->model->where($ilikeParam, 'ilike', '%' . $filterParams[$ilikeParam] . '%');
                    unset($filterParams[$ilikeParam]);
                }
            }
        }

        if (!empty($filterParams)) {
            $this->model = $this->model->where($filterParams);
        }

        return $this->model->get();
    }

    public function getAllWithIlikeRuleParamWithPagination($count, $filterParams, $ilikeParams)
    {
        $this->model = $this->model->with($this->model->relations());

        if (!empty($ilikeParams)) {
            foreach ($ilikeParams as $ilikeParam) {
                if (array_key_exists($ilikeParam, $filterParams)) {
                    $this->model = $this->model->where($ilikeParam, 'ilike', '%' . $filterParams[$ilikeParam] . '%');
                    unset($filterParams[$ilikeParam]);
                }
            }
        }

        if (!empty($filterParams)) {
            $this->model = $this->model->where($filterParams);
        }

        return $this->model
            ->paginate($count)
            ->items();
    }

    public function getById($id)
    {
        $this->model = $this->model->with($this->model->relations());

        return $this->model->findOrfail($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function firstOrCreate($data)
    {
        return $this->model->firstOrCreate($data);
    }

    public function update(Model $model, $data)
    {
        $model->update($data);

        return $model;
    }

    public function updatePartial(Model $model, $data)
    {
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();

        return $model->refresh();
    }

    public function delete(Model $model)
    {
        $model->delete();
    }
}
