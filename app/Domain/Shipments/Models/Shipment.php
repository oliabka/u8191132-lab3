<?php

namespace App\Domain\Shipments\Models;

use App\Domain\Items\Models\Item;

use App\Domain\Shipments\Models\Factories\ShipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nette\Utils\DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shipment
 * @package app\Domain\Shipments\Models
 *
 * @property integer $id
 * @property string $supplier
 * @property integer $amount
 * @property DateTime $date_time
 * @property integer $item_id
 */
class Shipment extends Model
{

    use HasFactory;

    protected $table = 'shipments';

    protected $fillable = [
        'supplier',
        'amount',
        'date_time',
        'item_id'
    ];

    public static function factory(): ShipmentFactory
    {
        return ShipmentFactory::new();
    }

    /**
     * Defines one-to-many relation for tables
     *
     * @returns BelongsTo
     */
    public function addresses(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

}
