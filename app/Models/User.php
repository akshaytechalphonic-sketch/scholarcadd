<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
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

    public function generate2FASecret()
    {
        $google2fa = new Google2FA();
        $this->google2fa_secret = $google2fa->generateSecretKey();
        $this->save();

        return $this->google2fa_secret;
    }
    public function verify2FAToken($token)
    {
        $google2fa = new Google2FA();
        $check = $google2fa->verifyKey($this->google2fa_secret, $token);
        return $check;
    }
    public function is2FAEnabled()
    {
        return !empty($this->google2fa_secret);
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->where('status', 'Active')->orderBy('id', 'DESC')->where('company_id',$cmp);
    }

    public function Employee()
    {
        $userModel = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($userModel, 'emp_id');
    }

    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }

    public function userCompany()
    {
        $model = get_class(get_model_instance('UserCompany','DynamicMaster'));
        return $this->hasMany($model, 'user_id');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->whereHas('userCompany', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });
    }

     public function getJWTIdentifier()
    {
        return $this->getKey(); // returns the primary key
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
