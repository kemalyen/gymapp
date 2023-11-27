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
        'created_at' => 'datetime:Y-m-d',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getPhoneNumberAttribute()
    {
        return $this->profile->phone;
    }

    public function getAddressAttribute()
    {
        $profile = $this->profile;
        if ($profile){
            $address = $profile->address_line_1 .PHP_EOL . '<br>'. $profile->address_line_2 .PHP_EOL . '<br>'. $profile->porst_code .PHP_EOL . '<br>'. $profile->city .' / '. $profile->country;
            return $address;
        }
        return;
     }

    public function getActiveMembership(): ?Membership
    {
        $membership = $this->memberships()->where('status', 1)->first();
        if ($membership) {
            return $membership;
        }
        return null;
    }


    public function getMemberStatusAttribute(): ?string
    {
        $membership = $this->memberships()->where('status', 1)->first();
        if ($membership) {
            return 'Active';
        }
        return 'Inactive';
    }


    public function getMembershipStartedAtAttribute()
    {
        $membership = $this->memberships()->where('status', 1)->first();
        if ($membership) {
            return $membership->start_date->format('d M Y');
        }
        return 'Inactive';
    }


    public function getMembershipEndingAtAttribute()
    {
        $membership = $this->memberships()->where('status', 1)->first();
        if ($membership) {
            return $membership->end_date->format('d M Y');
        }
        return 'Inactive';
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

    public function getMemberSinceAttribute()
    {
        return $this->created_at->format('d M Y');
    }
 
}
