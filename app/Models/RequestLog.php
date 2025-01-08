<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Pageble;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model {
    protected $table = 'request_logs';

    protected $fillable = [
        'id',
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
    ];

    protected $casts = [
        'query' => 'array',
        'body' => 'array',
        'headers' => 'array',
        'response' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
