<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use App\Utils\HttpResponse;
use App\Utils\HttpResponseCode;

class UserController extends Controller {
    use HttpResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return $this->success(User::all(), HttpResponseCode::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $creds = $request->only(['email', 'name']);
        $validate = Validator::make($creds, [
            'email' => 'required|email',
            'name' => 'required',
        ],[
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'name.required' => 'Name is required',
        ]);
        foreach ($validate->errors()->all() as $error) {
            return $this->error($error, HttpResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = User::where('email', $creds['email'])->first();
        if ($user) {
            return $this->error('User already exists', HttpResponseCode::HTTP_CONFLICT);
        }

        $user = User::create([
            'email' => $creds['email'],
            'name' => $creds['name'],
        ]);

        $defaultRoleSlug = config('hydra.default_user_role_slug', 'user');
        // $user->roles()->attach(Role::where('slug', $defaultRoleSlug)->first());
        UserRole::create(
            [
                'user_id' => $user->id,
                'role_id' => Role::where('slug', $defaultRoleSlug)->first()->id,
            ]
        );

        return $this->success($user->load('roles'), HttpResponseCode::HTTP_CREATED);
    }

    /**
     * Authenticate an user and dispatch token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $creds = $request->only('email', 'password');
        $validate = Validator::make($creds, [
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'password.required' => 'Password is required',
        ]);
        foreach ($validate->errors()->all() as $error) {
            return $this->error($error, HttpResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $creds['email'])->first();
        if (! $user || env('API_SECRET') != $creds['password']) {
            return $this->error('Invalid credentials ', HttpResponseCode::HTTP_UNAUTHORIZED);
        }

        if (config('hydra.delete_previous_access_tokens_on_login', false)) {
            $user->tokens()->delete();
        }
        $roles = $user->roles->pluck('slug')->all();

        $plainTextToken = $user->createToken('hydra-api-token', $roles)->plainTextToken;

        return $this->success([
            'token' => $plainTextToken,
            'id' => $user->id,
        ], HttpResponseCode::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\User  $user
     */
    public function show(User $user) {
        return $this->success($user->load('roles'), HttpResponseCode::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return User
     *
     * @throws MissingAbilityException
     */
    public function update(Request $request, User $user) {
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->email_verified_at = $request->email_verified_at ?? $user->email_verified_at;

        //check if the logged in user is updating it's own record

        $loggedInUser = $request->user();
        if ($loggedInUser->id == $user->id) {
            $user->update();
        } elseif ($loggedInUser->tokenCan('admin') || $loggedInUser->tokenCan('super-admin')) {
            // dd($request->all());
            $user->update();
        } else {
            throw new MissingAbilityException('Not Authorized');
        }

        return $this->success($user, HttpResponseCode::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRoles = $user->roles;

        if ($userRoles->contains($adminRole)) {
            //the current user is admin, then if there is only one admin - don't delete
            $numberOfAdmins = Role::where('slug', 'admin')->first()->users()->count();
            if (1 == $numberOfAdmins) {
                return $this->error('Cannot delete the only admin', HttpResponseCode::HTTP_FORBIDDEN);
            }
        }
        $name = $user->name;
        $user->delete();

        return $this->success(['message' => 'User '.$name.' Deleted'], HttpResponseCode::HTTP_OK);
    }

    /**
     * Return Auth user
     *
     * @param  Request  $request
     * @return mixed
     */
    public function me(Request $request) {
        return $request->user();
    }
}
