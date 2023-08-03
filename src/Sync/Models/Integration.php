<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Integration extends Model
{
    /** @var string имя таблицы модели  */
    protected $table = 'integrations';

    /** @var string первичный ключ таблицы */
    protected $primaryKey = 'id';

    /** @var bool отключение полей таблицы created_at/updated_at */
    public $timestamps = false;

    /** @var string[] разрешаем запись к полям в массиве */
    protected $fillable = [
        'id',
        'integration_id',
        'secret_key',
    ];

    /**
     * @return BelongsToMany - связь между Integration и Account - Многие ко Многим
     */
    public function account()
    {
        return $this->belongsToMany(
            Account::class,
            'account_integration',
            'integration_id',
            'account_id'
        );
    }
}