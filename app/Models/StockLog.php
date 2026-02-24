<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    use HasUuids;

    protected static function booted(): void
    {
        static::addGlobalScope(new BranchScope);
    }

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'branch_id',
        'ingredient_id',
        'user_id',
        'amount',
        'type',
        'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
