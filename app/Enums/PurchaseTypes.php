<?php

namespace App\Enums;

enum PurchaseTypes: string
{
    case SEASON_TICKET = 'abonado';
    case SERIE = 'serie';
    case MATCH = 'partido';
}
