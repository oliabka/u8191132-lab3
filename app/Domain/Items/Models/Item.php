<?php

namespace App\Domain\Items\Models;

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
class Address extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'amount',
    ];
}
