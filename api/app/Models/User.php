<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
 * @property Company $company
 * @property Collection|MailTemplate[] $mail_templates
 * @property Collection|ProjectCharge[] $project_charges
 * @property Collection|ProjectTrap[] $project_traps
 * @property Collection|Trainer[] $trainers
 * @property Collection|TransmissionRecord[] $transmissions
 * @property Collection|Trap[] $traps
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';

	protected $casts = [
		'company_id' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'company_id',
        'email',
        'password',
		'cognito_sub',
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

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function mail_templates()
	{
		return $this->hasMany(MailTemplate::class);
	}

	public function project_charges()
	{
		return $this->hasMany(ProjectCharge::class);
	}

	public function project_traps()
	{
		return $this->hasMany(ProjectTrap::class);
	}

	public function trainers()
	{
		return $this->hasMany(Trainer::class);
	}

	public function transmissions()
	{
		return $this->hasMany(TransmissionRecord::class);
	}

	public function traps()
	{
		return $this->hasMany(Trap::class);
	}

	public function roles()
	{
		return $this->belongsToMany(Role::class, 'user_roles')
					->withTimestamps();
	}
}
