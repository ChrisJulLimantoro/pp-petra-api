<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Utils\HttpResponse;
use App\Utils\HttpResponseCode;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller {
    use HttpResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return $this->success(Role::with('users','roleRoutes')->get(), HttpResponseCode::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $data = $request->only(['name', 'slug']);
        $validate = Validator::make($data, [
            'name' => 'required',
            'slug' => 'required',
        ],[
            'name.required' => 'Name is required',
            'slug.required' => 'Slug is required',
        ]);
        if ($validate->fails()) {
            return $this->error($validate->errors(), HttpResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
        $existing = Role::where('slug', $data['slug'])->first();

        if (! $existing) {
            $role = Role::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
            ]);

            return $this->success($role, HttpResponseCode::HTTP_CREATED);
        }

        return $this->error('role already exists', HttpResponseCode::HTTP_CONFLICT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \App\Models\Role $role
     */
    public function show(Role $role) {
        return $this->success($role->load('users','roleRoutes'), HttpResponseCode::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|Role
     */
    public function update(Request $request, Role $role = null) {
        if (! $role) {
            return $this->error('role not found', HttpResponseCode::HTTP_NOT_FOUND);
        }

        $role->name = $request->name ?? $role->name;

        if ($request->slug) {
            if ($role->slug != 'admin' && $role->slug != 'super-admin') {
                //don't allow changing the admin slug, because it will make the routes inaccessbile due to failed ability check
                $role->slug = $request->slug;
            }
        }

        $role->update();

        return $this->success($role, HttpResponseCode::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
        // dd($role);
        if ($role->slug != 'admin' && $role->slug != 'super-admin') {
            //don't allow changing the admin slug, because it will make the routes inaccessbile due to faile ability check
            $name = $role->name;
            $role->delete();

            return $this->success(["message" =>'role '.$name .' deleted'], HttpResponseCode::HTTP_OK);
        }

        return $this->error('cannot delete admin role', HttpResponseCode::HTTP_FORBIDDEN);
    }
}
