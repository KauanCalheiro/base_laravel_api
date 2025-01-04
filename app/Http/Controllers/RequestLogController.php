<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;

class RequestLogController extends Controller {
    public function __construct() {
        $this->authorizeResource(RequestLog::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            $logs = RequestLog::query()
                ->search($request)
                ->filter($request)
                ->paginateQuery($request)
                ->get();

            $countAll = RequestLog::query()
                ->search($request)
                ->filter($request)
                ->count();

            return ResponseService::success($logs, $countAll);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }
}
