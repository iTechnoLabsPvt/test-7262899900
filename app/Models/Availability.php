<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'day_id'
    ];

    const MONDAY = 1;

    const TUESDAY = 2;

    const WEDNESDAY = 3;

    const THURSDAY = 4;

    const FRIDAY = 5;

    const SATURDAY = 6;

    const SUNDAY = 7;

    public static function getDayOptions()
    {
        return [
            self::MONDAY => 'Monday',
            self::TUESDAY => 'Tuesday',
            self::WEDNESDAY => 'Wednesday',
            self::THURSDAY => 'Thursday',
            self::FRIDAY => 'Friday',
            self::SATURDAY => 'Saturday',
            self::SUNDAY => 'Sunday',
        ];
    }

    public static function getDay($date)
    {
        $day = date('l', strtotime($date));
        switch ($day) {
            case 'Monday';
                return self::MONDAY;
                break;
            case 'Tuesday';
                return self::TUESDAY;
                break;
            case 'Wednesday';
                return self::WEDNESDAY;
                break;
            case 'Thursday';
                return self::THURSDAY;
                break;
            case 'Friday';
                return self::FRIDAY;
                break;
            case 'Saturday';
                return self::SATURDAY;
                break;
            case 'Sunday';
                return self::SUNDAY;
                break;
        }
    }
}
