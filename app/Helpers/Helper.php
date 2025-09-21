<?php

namespace App\Helpers;

class Helper
{
    /**
     * Format currency
     */
    public static function formatCurrency($amount, $currency = 'IDR')
    {
        return $currency . ' ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format date
     */
    public static function formatDate($date, $format = 'd/m/Y')
    {
        return $date ? $date->format($format) : '-';
    }

    /**
     * Format datetime
     */
    public static function formatDateTime($datetime, $format = 'd/m/Y H:i')
    {
        return $datetime ? $datetime->format($format) : '-';
    }

    /**
     * Generate random string
     */
    public static function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    /**
     * Calculate working hours
     */
    public static function calculateWorkingHours($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        return $checkIn->diffInHours($checkOut);
    }
}
