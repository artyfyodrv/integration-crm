<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $table = 'accesses';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'base_domain',
        'access_token',
        'refresh_token',
        'expires',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}