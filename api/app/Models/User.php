<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property int $company_id
 * @property string $cognito_sub
 * @property string $name_family
 * @property string $name_first
 * @property string|null $name_family_kana
 * @property string|null $name_first_kana
 * @property string|null $tel
 * @property bool $is_active
 * @property string $state
 * @property string|null $department
 * @property string|null $position
 * @property string|null $part
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'users';

	protected $casts = [
		'company_id' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'company_id',
        'email',
        'password',
		'name_family',
		'name_first',
		'name_family_kana',
		'name_first_kana',
		'tel',
		'is_active',
		'state',
		'department',
		'position',
		'part'
	];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
