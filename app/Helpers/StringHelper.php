<?php

if (!function_exists('maskWalletAccountNumber'))
{
    function maskWalletAccountNumber($value) {
        if ($value && strlen($value) >= 4) {

            $maskLength = strlen($value) - 4;
            $visibleSection = substr($value, -4);
            $maskedSection = str_repeat('*', $maskLength);

            return $maskedSection . $visibleSection;
        }

        return $value;
    }
}
