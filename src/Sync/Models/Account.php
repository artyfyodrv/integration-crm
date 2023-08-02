<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    /** @var string имя таблицы модели */
    protected $table = 'accounts';

    /** @var string первичный ключ таблицы */
    protected $primaryKey = 'id';

    /** @var bool отключение полей таблицы created_at/updated_at */
    public $timestamps = false;

    /** @var string[] разрешаем запись к полям в массиве */
    protected $fillable = [
        'id',
    ];

    /**
     * @return HasOne - связь между Account и Access - Один к Одному
     */
    public function access()
    {
        return $this->hasOne(Access::class);
    }

    /**
     * @return HasMany - связь между Account и Contact - один ко многим
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @return BelongsToMany - связь между Account и Integration - Многие ко Многим
     */
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