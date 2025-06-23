<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_gender_id',
        'first_name',
        'last_name',
        'middle_name',
        'username',
        'birthdate',
        'email',
        'email_verified_at',
        'phone_number',
        'color',
        'is_active',
        'password',
    ];

    protected $appends = [
        'is_new',
        'full_name'
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

    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function getIsNewAttribute()
    {
        return $this->created_at->gt(now()->subMinutes(20));
    }

    public function userGender()
    {
        return $this->belongsTo(UserGender::class);
    }

    public function userRoles()
    {
        return $this->belongsToMany(UserRole::class, 'role_user', 'user_id', 'user_role_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function hasRole($role)
    {
        return $this->userRoles->contains('name', $role);
    }

    public function globalImages()
    {
        return $this->belongsToMany(GlobalImage::class, 'global_image_user', 'user_id', 'global_image_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function stadiums()
    {
        return $this->belongsToMany(Stadium::class, 'stadium_user', 'user_id', 'stadium_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function seasonTickets()
    {
        return $this->hasMany(SeasonTicket::class);
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class, 'seller_user_opening_id');
    }

    public function cashRegisterActive($ticket_office_id)
    {
        return $this->cashRegisters()
            ->where('ticket_office_id', $ticket_office_id)
            ->where('is_open', 1)
            ->first();
    }

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }

    public function EventSeatCatalogues()
    {
        return $this->hasMany(EventSeatCatalog::class);
    }

    public function eventSeatCatalogSaleTickets()
    {
        return $this->hasMany(EventSeatCatalogSaleTicket::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.($this->middle_name ? ' '.$this->middle_name : '').' '.$this->last_name;
    }
}
