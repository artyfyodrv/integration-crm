<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    /** @var string имя таблицы модели  */
    protected $table = 'accesses';

    /** @var string первичный ключ таблицы */
    protected $primaryKey = 'id';

    /** @var bool отключение полей таблицы created_at/updated_at */
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'base_domain',
        'access_token',
        'refresh_token',
        'expires',
        'unisender_key',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo - обратная связь 1 к 1
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}