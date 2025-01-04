<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Pageble;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use Filterable, Searchable, Pageble;

    protected $table = 'request_logs';

    protected $fillable = [
        'id',
        'ref_user',
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
    ];

    protected $casts = [
        'query' => 'array',
        'body' => 'array',
        'headers' => 'array',
        'response' => 'array'
    ];
}
