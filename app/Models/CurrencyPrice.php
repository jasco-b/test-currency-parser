<?php

namespace App\Models;

use App\Filters\CurrencyPriceFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CurrencyPrice
 *
 * @property int $id
 * @property int $currency_id
 * @property float $value
 * @property \Illuminate\Support\Carbon $date
 * @property int $nominal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyPrice whereValue($value)
 * @mixin \Eloquent
 * @property Currency $currency
 * @method  static filter($request);
 */
class CurrencyPrice extends Model
{
    protected $fillable = [
        'value', 'date', 'nominal', 'currency_id',
    ];

    protected $casts = [
        'date'=>'date:d.m.Y',
        'created_at'=>'datetime:Y-m-d H:i:s'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeFilter($query, $request)
    {
        return (new CurrencyPriceFilter($query))
            ->filter($request);
    }
}
