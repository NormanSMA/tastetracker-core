<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use App\Traits\HasStockDeltas;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasStockDeltas, HasUuids, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope(new BranchScope);
    }

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * stock_quantity is NOT fillable — only modifiable via adjustStock() (Blueprint 1.3).
     */
    protected $fillable = [
        'branch_id',
        'category_id',
        'name',
        'unit',
        'min_stock',
        'is_active',
    ];

    protected $casts = [
        'stock_quantity' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
