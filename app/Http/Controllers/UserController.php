<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserPermissionRequest;
use App\Http\Requests\UserRoleRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function __construct() {
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            $users = User::query()
                ->search($request)
                ->filter($request)
                ->withRelations($request)
                ->paginateQuery($request)
                ->get();

            $count = User::query()
                ->search($request)
                ->filter($request)
                ->count();

            return ResponseService::success(data: $users, count: $count);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request) {
        try {
            $user = User::create($request->validated());
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) {
        try {
            $user->load(request('with', []));
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user) {
        try {
            $user->update($request->validated());
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) {
        try {
            $user->delete();
            return ResponseService::success($user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    public function assignRole(UserRoleRequest $request, User $user) {
        try {
            $user->assignRole($request->role);
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    public function revokeRole(UserRoleRequest $request, User $user) {
        try {
            $user->removeRole($request->role);
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    public function assignPermission(UserPermissionRequest $request, User $user) {
        try {
            $user->givePermissionTo($request->permission);
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    public function revokePermission(UserPermissionRequest $request, User $user) {
        try {
            $user->revokePermissionTo($request->permission);
            return ResponseService::success(data: $user);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }
}
