<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    /** @var string имя таблицы модели  */
    protected $table = 'contacts';

    /** @var bool отключение полей таблицы created_at/updated_at */
    public $timestamps = false;

    /** @var string[] разрешаем запись к полям в массиве */
    protected $fillable = [
        'name',
        'email',
        'account_id',
    ];

    /**
     * @return BelongsTo - обртная вязь между Contact и Account - Один ко Многим
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}