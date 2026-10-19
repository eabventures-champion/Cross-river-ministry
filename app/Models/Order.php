<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'shipped', 'completed', 'cancelled'];

    protected $fillable = ['reference', 'name', 'email', 'phone', 'address', 'notes', 'total', 'status'];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
