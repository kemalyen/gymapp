<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true; //str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function getPhoneNumberAttribute()
    {
        return $this->profile->phone;
    }


    public function getActiveMembership(): ?Membership
    {
        $membership = $this->memberships()->where('status', 1)->first();
        if ($membership) {
            return $membership;
        }
        return null;
    }

    public function getMembershipEndingAttribute()
    {
        $membership = $this->memberships()->where('status', 1)->first();
        if ($membership) {
            return $membership->end_date->diffForHumans();
        }
        return 'Inactive';
    }

    public function getContractNameAttribute()
    {
        $membership = $this->getActiveMembership();
        if ($membership) {
            return $membership?->contract?->name;
        }
        return $this->membership;
    }
 
}
