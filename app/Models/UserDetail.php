<?php

namespace App\Models;

use App\Enums\AgamaEnum;
use App\Enums\GenderEnum;
use App\Enums\BidangMafindoEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
     * @return array{
     *     tgl_lahir: 'date:d/m/Y',
     *     gender: 'App\Enums\GenderEnum',
     *     agama: 'App\Enums\AgamaEnum',
     *     bidang_mafindo: 'App\Enums\BidangMafindoEnum',
     *     pendidikan: 'object',
     *     pekerjaan: 'object',
     *     sertifikat: 'object'
     * }
     */
    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date:d/m/Y',
            'gender' => GenderEnum::class,
            'agama' => AgamaEnum::class,
            'bidang_mafindo' => BidangMafindoEnum::class,
            'pendidikan' => 'object',
            'pekerjaan' => 'object',
            'sertifikat' => 'object',
        ];
    }

    /**
     * Get the social media information of the user.
     * If the user has no social media data, returns default values.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<object, never>
     */
    protected function medsos(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $medsos = json_decode($value, true) ?: [];
                $list = ['facebook', 'instagram', 'tiktok', 'twitter'];

                $medsos = array_merge(array_fill_keys($list, null), $medsos);

                return (object) $medsos;
            },
        );
    }
}
