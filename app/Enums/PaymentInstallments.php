<?php

namespace App\Enums;

enum PaymentInstallments: int
{
    case TRES_MESES = 3;
    case SEIS_MESES = 6;

    public static function toArray(): array {
        return [
            self::TRES_MESES->value,
            self::SEIS_MESES->value,
        ];
    }
}
