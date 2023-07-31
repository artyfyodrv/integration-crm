<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'id',
    ];

    public function access()
    {
        return $this->hasOne(Access::class);
    }

    public function contact()
    {
        return $this->hasMany(Contact::class);
    }

    public function integration()
    {
        return $this->belongsToMany(
            Integration::class,
            'account_integration',
            'account_id',
            'integration_id',
        );
    }
}