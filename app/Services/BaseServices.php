<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class BaseService
{
    protected $model;

    protected $repository;

    protected $resource;

    protected $redisKey;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->repository = $model->repository();
        $this->resource = $model->resource();
    }

    public function getAll($filterParams = [])
    {
        ServiceUtils::validateFilterParams($this->model, $filterParams);

        $data = $this->repository->getAll($filterParams);

        return $this->resource::collection($data);
    }

    public function getAllWithPagination($count, $filterParams = [])
    {
        ServiceUtils::validateFilterParams($this->model, $filterParams);

        $data = $this->repository->getAllWithPagination($count, $filterParams);

        return $this->resource::collection($data);
    }

    public function getById($id)
    {
        $data = $this->repository->getById($id);

        return new $this->resource($data);
    }

    public function create($request)
    {
        $request = array_map('trim', $request);

        $data = $this->repository->create($request);
        $data = new $this->resource($data);

        return $data;
    }

    public function update($id, $request)
    {
        $dataById = $this->repository->getById($id);
        $request = array_map('trim', $request);

        $data = $this->repository->update($dataById, $request);
        $data = new $this->resource($data);

        return $data;
    }

    public function updatePartial($id, $request)
    {
        $dataById = $this->repository->getById($id);
        $request = array_map('trim', $request);

        $data = $this->repository->updatePartial($dataById, $request);
        $data = new $this->resource($data);

        return $data;
    }

    public function delete($id)
    {
        $dataById = $this->repository->getById($id);
        $this->repository->delete($dataById);
    }
}
