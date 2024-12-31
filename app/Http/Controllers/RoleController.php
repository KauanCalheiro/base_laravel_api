<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Policies\RolePolicy;
use App\Services\ResponseService;
use Exception;
class RoleController extends Controller {
    public function __construct() {
        $this->authorizeResource(Role::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        try {
            return ResponseService::success(Role::all());
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role) {
        try {
            return ResponseService::success($role);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request) {
        try {
            return ResponseService::success(Role::create($request->validated()));
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role) {
        try {
            if (!$role->update($request->validated())) {
                throw new Exception(__('Error updating role'));
            }
            return ResponseService::success($role);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role) {
        try {
            if (!$role->deleteQuietly()) {
                throw new Exception(__('Error deleting role'));
            }
            return ResponseService::success($role);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }
}