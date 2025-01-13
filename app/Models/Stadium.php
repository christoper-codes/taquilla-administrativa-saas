<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    protected $table = 'stadiums';

    protected $fillable = [
        'leading_company_id',
        'global_address_id',
        'name',
        'club_name',
        'email',
        'phone_number',
        'is_active'
    ];

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function leadingCompany()
    {
        return $this->belongsTo(LeadingCompany::class);
    }

    public function globalAddress()
    {
        return $this->belongsTo(GlobalAddress::class);
    }

    public function globalImages()
    {
        return $this->belongsToMany(GlobalImage::class, 'global_image_stadium', 'stadium_id', 'global_image_id')
                ->withPivot('is_active')
                ->withTimestamps();
    }

    public function users()
    {
        $this->belongsToMany(User::class, 'stadium_user', 'stadium_id', 'user_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function globalSeason()
    {
        return $this->hasMany(GlobalSeason::class);
    }

    public function zoneTypes()
    {
        return $this->hasMany(ZoneType::class);
    }

    public function seatTypes()
    {
        return $this->hasMany(SeatType::class);
    }

    public function priceCatalogues()
    {
        return $this->hasMany(PriceCatalogue::class);
    }

    public function seatCatalogues()
    {
        return $this->hasMany(SeatCatalogue::class);
    }

    public function institutions()
    {
        return $this->hasMany(Institution::class);
    }

    public function leagueTypes()
    {
        return $this->hasMany(LeagueType::class);
    }

   public function ticketOffices()
    {
        return $this->hasMany(TicketOffice::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function courtesyTypes()
    {
        return $this->hasMany(CourtesyType::class);
    }

    public function reasonAgreements()
    {
        return $this->hasMany(ReasonAgreement::class);
    }

    public function saleDebtors()
    {
        return $this->hasMany(SaleDebtor::class);
    }

}
