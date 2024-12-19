<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TempUser extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'no_relawan',
        'branch_id',
        'user_detail_id',
    ];

    /**
     * @return BelongsTo<\App\Models\UserDetail, $this>
     */
    public function userDetail(): BelongsTo
    {
        return $this->belongsTo(UserDetail::class);
    }

    /**
     * @return BelongsTo<\App\Models\Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
