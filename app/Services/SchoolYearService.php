<?php

namespace App\Services;

class SchoolYearService
{
    /**
     * Format a school year string consistently.
     *
     * @param string $schoolYear
     * @return string
     */
    public static function format($schoolYear)
    {
        // Remove any spaces around the dash
        return str_replace(' - ', '-', $schoolYear);
    }

    /**
     * Check if two school year strings match, regardless of format.
     *
     * @param string $schoolYear1
     * @param string $schoolYear2
     * @return bool
     */
    public static function compare($schoolYear1, $schoolYear2)
    {
        return self::format($schoolYear1) === self::format($schoolYear2);
    }

    /**
     * Get a list of recent school years for dropdowns.
     *
     * @param int $count Number of years to generate
     * @param int $startYear Starting year (defaults to current year)
     * @return array
     */
    public static function getRecentYears($count = 5, $startYear = null)
    {
        if ($startYear === null) {
            $startYear = date('Y');
        }

        $years = [];
        for ($i = 0; $i < $count; $i++) {
            $year = $startYear - $i;
            $nextYear = $year + 1;
            $years[$year . '-' . $nextYear] = $year . '-' . $nextYear;
        }

        return $years;
    }
}
