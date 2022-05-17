<?php

namespace App\Domain\Items\Models;

use App\Domain\Items\Models\Factories\ItemFactory;
use App\Domain\Shipments\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Item
 * @package app\Domain\Items\Models
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $amount
 */
class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'amount',
    ];

    protected $attributes = [
        'description' => 'no description',
        'amount' => 0,
    ];

    public static function factory(): ItemFactory
    {
        return ItemFactory::new();
    }

    /**
     * Defines one-to-many relation for tables
     *
     * @returns HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
