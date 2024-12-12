<?php

namespace App\Models;

use App\Enums\AgamaEnum;
use App\Enums\BidangMafindoEnum;
use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'panggilan',
        'tgl_lahir',
        'gender',
        'agama',
        'disabilitas',
        'no_wa',
        'no_hp',
        'alamat',
        'bidang_keahlian',
        'bidang_mafindo',
        'thn_bergabung',
        'pdr',
        'medsos',
        'pendidikan',
        'pekerjaan',
        'sertifikat',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date:d/m/Y',
            'gender' => GenderEnum::class,
            'agama' => AgamaEnum::class,
            'bidang_mafindo' => BidangMafindoEnum::class,
            'medsos' => 'object',
            'pendidikan' => 'object',
            'pekerjaan' => 'object',
            'sertifikat' => 'object',
        ];
    }
}
