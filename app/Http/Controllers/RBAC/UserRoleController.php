<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Utils\HttpResponse;
use App\Utils\HttpResponseCode;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller {
    use HttpResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\UserRole  $userRole
     */
    public function index() {
        return UserRole::with('role','user')->get();
    }

    public function getByUser(User $user) {
        return $user->load('roles');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \App\Models\User  $user
     */
    public function store(Request $request, User $user) {
        $data = $request->only(['role_id']);
        $validate = Validator::make($data, 
        [
            'role_id' => 'required|uuid',
        ],
        [
            'role_id.required' => 'Role is required',
            'role_id.uuid' => 'Role is not valid',
        ]);

        foreach ($validate->errors()->all() as $error) {
            return $this->error($error, HttpResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
        $role = Role::find($data['role_id']);
        if ($user->roles()->find($data['role_id'])) {
            return $this->error('User already has this role', HttpResponseCode::HTTP_CONFLICT);
        }
        UserRole::create(
            [
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]
        );
        return $this->success($user->load('roles'), HttpResponseCode::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return \App\Models\User  $user
     */
    // public function destroy(User $user, Role $role) {
    //     $user->roles()->detach($role);

    //     return $this->success($user->load('roles'), HttpResponseCode::HTTP_OK);
    // }
    public function destroy(UserRole $userRole) {
        if(!$userRole){
            return $this->error('User Role not found', HttpResponseCode::HTTP_NOT_FOUND);
        }

        $userRole->delete();

        return $this->success(['message' => 'User Role Deleted!'], HttpResponseCode::HTTP_OK);
    }

    public function unassignRole(User $user, Role $role) {
        $unassigned = $user->roles()->detach($role->id);

        if (!$unassigned) {
            return $this->error('Role unassignment failed!', HttpResponseCode::HTTP_NOT_FOUND);
        }

        return $this->success('Role unassigned Successfully!', HttpResponseCode::HTTP_OK);
    }
}
