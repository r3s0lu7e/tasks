<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeriodCalendar extends Model
{
    use HasFactory;

    protected $table = 'period_calendar';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'cycle_length',
        'period_length',
        'flow_intensity',
        'symptoms',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user that owns the period calendar entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the predicted next period start date.
     */
    public function getNextPeriodDateAttribute()
    {
        return $this->start_date->addDays($this->cycle_length);
    }

    /**
     * Get the predicted ovulation date.
     */
    public function getOvulationDateAttribute()
    {
        return $this->start_date->addDays($this->cycle_length - 14);
    }

    /**
     * Get the fertility window start date.
     */
    public function getFertilityWindowStartAttribute()
    {
        return $this->ovulation_date->subDays(5);
    }

    /**
     * Get the fertility window end date.
     */
    public function getFertilityWindowEndAttribute()
    {
        return $this->ovulation_date->addDays(1);
    }

    /**
     * Check if a given date is within the period.
     */
    public function isWithinPeriod(Carbon $date)
    {
        if (!$this->end_date) {
            $endDate = $this->start_date->copy()->addDays($this->period_length - 1);
        } else {
            $endDate = $this->end_date;
        }

        return $date->between($this->start_date, $endDate);
    }

    /**
     * Check if a given date is within the fertility window.
     */
    public function isWithinFertilityWindow(Carbon $date)
    {
        return $date->between($this->fertility_window_start, $this->fertility_window_end);
    }

    /**
     * Get the most recent period for a user.
     */
    public static function getLatestForUser($userId)
    {
        return static::where('user_id', $userId)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    /**
     * Get periods for a specific month and year.
     */
    public static function getPeriodsForMonth($userId, $year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        return static::where('user_id', $userId)
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<=', $endOfMonth)
                            ->where(function ($q2) use ($startOfMonth) {
                                $q2->whereNull('end_date')
                                    ->orWhere('end_date', '>=', $startOfMonth);
                            });
                    });
            })
            ->orderBy('start_date')
            ->get();
    }
}
