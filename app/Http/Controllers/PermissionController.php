<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Policies\PermissionPolicy;
use App\Services\ResponseService;
use Exception;

class PermissionController extends Controller {
    public function __construct() {
        $this->authorizeResource(Permission::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        try {
            return ResponseService::success(Permission::all());
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission) {
        try {
            return ResponseService::success($permission);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request) {
        try {
            return ResponseService::success(Permission::create($request->validated()));
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission) {
        try {
            if (!$permission->update($request->validated())) {
                throw new Exception(__('Error updating permission'));
            }
            return ResponseService::success($permission);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission) {
        try {
            if (!$permission->deleteQuietly()) {
                throw new Exception(__('Error deleting permission'));
            }
            return ResponseService::success($permission);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }
}
