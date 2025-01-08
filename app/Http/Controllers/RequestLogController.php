<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RequestLogController extends Controller {
    public function __construct() {
        $this->authorizeResource(RequestLog::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            $logs = QueryBuilder::for(RequestLog::class)
                ->allowedFilters([
                    AllowedFilter::exact('id'),
                    'message',
                    'level',
                    'context',
                    'user_id',
                    'method',
                    'path',
                    'query',
                    'body',
                    'headers',
                    'ip_address',
                    'user_agent',
                    'response_status',
                    'execution_time_in_ms',
                    'response',
                ])
                ->allowedIncludes([
                    'user',
                ])
                ->allowedSorts([
                    'id',
                    'created_at',
                    'level',
                ])
                ->jsonPaginate();

            return ResponseService::success(data: $logs, count: $logs->toArray()['total']);
        } catch (Exception $e) {
            return ResponseService::error($e);
        }
    }
}
