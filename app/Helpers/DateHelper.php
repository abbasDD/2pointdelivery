<?php

// app/Helpers/DateHelper.php
namespace App\Helpers;

class DateHelper
{
    public static function formatTimestamp($timestamp, $format = 'Y-m-d H:i:s')
    {
        // Convert timestamp to a DateTime object
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);

        // Format the DateTime object according to the provided format
        return $dateTime->format($format);
    }
}
