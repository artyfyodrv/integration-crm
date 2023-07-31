<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $table = 'integrations';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'integrationId',
    ];

    public function account()
    {
        return $this->belongsToMany(
            Account::class,
            'account_table',
            'integration_id',
            'account_id'
        );
    }
}