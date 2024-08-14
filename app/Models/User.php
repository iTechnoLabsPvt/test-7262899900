<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'date_of_birth'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Add Availability for each day
     */
    public function addAvailability()
    {
        $days = Availability::getDayOptions();
        foreach ($days as $key => $val) {
            Availability::create([
                'user_id' => $this->id,
                'day_id' => $key
            ]);
        }
    }

    /**
     * Returns related availabilities
     */
    public function availabilty()
    {
        return $this->hasMany(Availability::class, 'user_id', 'id');
    }

    /**
     * Returns true if user is available on specific date
     */
    public function isUserAvailable($date)
    {
        $day = Availability::getDay($date);
        $availability = Availability::where('day_id', $day)->where('user_id', $this->id)->first();
        return ($availability) ? true : false;
    }

    /**
     * Returns the average rating of user
     */
    public function getAvgRating(){
        return Rating::where('user_id', $this->id)->avg('rating');
    }

    /**
     * Returns the average target of user
     */
    public function getAvgTarget(){
        return UploadedData::where('user_id', $this->id)->avg('target');
    }
}
