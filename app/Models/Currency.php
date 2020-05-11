<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $valuteID
 * @property string $numCode
 * @property string $charCode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CurrencyPrice[] $prices
 * @property-read int|null $prices_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereNumCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereValuteID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency wherecharCode($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{

    protected $fillable = [
        'valuteID', 'numCode', 'charCode'
    ];

    public function prices()
    {
        return $this->hasMany(CurrencyPrice::class);
    }
}
