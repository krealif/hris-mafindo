<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
        'tahun_bergabung',
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
            'medsos' => 'object',
            'pendidikan' => 'object',
            'pekerjaan' => 'object',
            'sertifikat' => 'object',
        ];
    }
}
