<?php

namespace App\Domain\Items\Models;

use App\Domain\Items\Models\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
