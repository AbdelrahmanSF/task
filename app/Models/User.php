<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','phone','avatar','bio','address','service_location','role','is_active'];
    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime','password' => 'hashed','role' => UserRole::class,'is_active' => 'boolean'];
    }

    public function isAdmin(): bool { return $this->role === UserRole::Admin; }
    public function isProvider(): bool { return $this->role === UserRole::Provider; }
    public function isCustomer(): bool { return $this->role === UserRole::Customer; }
    public function services(): HasMany { return $this->hasMany(Service::class, 'provider_id'); }
    public function bookingsAsCustomer(): HasMany { return $this->hasMany(Booking::class, 'customer_id'); }
    public function bookingsAsProvider(): HasMany { return $this->hasMany(Booking::class, 'provider_id'); }
    public function reviews(): HasMany { return $this->hasMany(Review::class, 'provider_id'); }
}
