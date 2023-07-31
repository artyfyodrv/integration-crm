<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}