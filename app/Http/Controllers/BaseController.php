<?php

namespace App\Http\Controllers;

use App\Utils\HttpResponse;
use App\Utils\HttpResponseCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use HttpResponse;

    public $model;

    protected $service;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->service = $model->service();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterParams = $request->query();

        $res = $this->service->getAll($filterParams);

        return $this->success($res);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestFillable = $request->only($this->model->getFillable());

        ControllerUtils::validateRequest($this->model, $requestFillable);

        $res = $this->service->create($requestFillable);

        return $this->success(
            $res,
            HttpResponseCode::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $res = $this->service->getById($id);

        return $this->success($res);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $requestFillable = $request->only($this->model->getFillable());

        ControllerUtils::validateRequest($this->model, $requestFillable);

        $res = $this->service->update(
            $id,
            $requestFillable,
        );

        return $this->success($res);
    }

    /**
     * Update the specified resource in storage partially.
     */
    public function updatePartial(Request $request, $id)
    {
        $fillableKey = [];
        foreach ($this->model->getFillable() as $field) {
            if ($request->has($field)) {
                $fillableKey[] = $field;
            }
        }

        $requestFillable = $request->only($fillableKey);

        ControllerUtils::validateRequest(
            $this->model,
            $requestFillable,
            isPatch: true,
        );

        $res = $this->service->updatePartial(
            $id,
            $requestFillable,
        );

        return $this->success($res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->service->delete($id);

        return $this->success(
            null,
            200
        );
    }
}
