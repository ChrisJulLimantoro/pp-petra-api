<?php

namespace App\Http\Controllers\RBAC;

use App\Models\RoleRoutes;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\HttpResponse;
use App\Utils\HttpResponseCode;
use Illuminate\Support\Facades\Validator;

class RoleRoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse;
    public function index()
    {
        return $this->success(RoleRoutes::with('role')->get(), HttpResponseCode::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only(['role_id', 'route', 'method','name']);
        $validate = Validator::make($data, [
            'role_id' => 'required|uuid',
            'route' => 'required',
            'method' => 'required',
            'name' => 'required'
        ],[
            'role_id.required' => 'Role is required',
            'role_id.uuid' => 'Role is not valid',
            'route.required' => 'Route is required',
            'method.required' => 'Method is required',
            'name.required' => 'Name is required'
        ]);
        foreach ($validate->errors()->all() as $error) {
            return $this->error($error, HttpResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
        if(RoleRoutes::where('role_id', $data['role_id'])->where('route', $data['route'])->where('method', $data['method'])->first()){
            return $this->error('Role already has this route and method', HttpResponseCode::HTTP_CONFLICT);
        }
        $roleRoute = RoleRoutes::create($data);
        return $this->success($roleRoute, HttpResponseCode::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoleRoutes $roleRoutes)
    {
        if(!$roleRoutes){
            return $this->error('Role Route not found', HttpResponseCode::HTTP_NOT_FOUND);
        }
        return $this->success($roleRoutes->load('roles'), HttpResponseCode::HTTP_OK);
    }
    public function getByRole($role_id)
    {
        if(!RoleRoutes::where('role_id', $role_id)->first()){
            return $this->error('Role Route not found', HttpResponseCode::HTTP_NOT_FOUND);
        }
        return $this->success(RoleRoutes::where('role_id', $role_id)->get(), HttpResponseCode::HTTP_OK);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoleRoutes $role_route = null)
    {
        // dd($role_route);
        $role_route->route = $request->route ?? $role_route->route;
        $role_route->method = $request->method ?? $role_route->method;
        $role_route->name = $request->name ?? $role_route->name;
        if (!$role_route->isDirty()) {
            return $this->error('You need to specify a different value to update', HttpResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
        $role_route->update();
        return $this->success($role_route, HttpResponseCode::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoleRoutes $role_route)
    {
        if(! $role_route ){
            return $this->error('Role Route not found', HttpResponseCode::HTTP_NOT_FOUND);
        }
        // dd($role_route);
        $name = $role_route->name;
        $role_route->delete();
        return $this->success(['message' => 'RoleRoutes '.$name.' Deleted!'], HttpResponseCode::HTTP_OK);
    }

    public function check(Request $request)
    {
        $route = $request->route;
        $method = $request->method;
        $user_id = $request->user_id;

        $user_pool = RoleRoutes::with(['role.users'])->where(['route'=>$route,'method'=>$method])->get()->pluck('role')->pluck('users')->toArray();
        $users = [];
        foreach($user_pool as $up){
            foreach($up as $p){
                if(!in_array($p['id'],$users)) $users[] = $p['id'];
            }
        }
        if(in_array($user_id,$users)){
            return $this->success(true);
        }
        return $this->success(false);
    }
}
