<?php

namespace App\Traits;

use App\Services\SchoolYearService;

trait FormatSchoolYearTrait
{
    /**
     * Set the school year attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setSchoolYearAttribute($value)
    {
        $this->attributes['school_year'] = SchoolYearService::format($value);
    }

    /**
     * Scope a query to filter by school year, accounting for different formats.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $schoolYear
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterBySchoolYear($query, $schoolYear)
    {
        $formattedYear = SchoolYearService::format($schoolYear);
        return $query->where('school_year', $formattedYear);
    }
}
