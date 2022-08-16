<?php

namespace App\Models;

use App\Helpers\Constants;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;
use Morilog\Jalali\Jalalian;

/**
 * @property string last_name
 * @property string first_name
 * @property mixed activity
 * @property mixed birth_date
 * @property mixed height
 * @property mixed weight
 * @property mixed age
 * @property mixed gender
 * @property mixed activity_ratio
 * @property mixed BMR
 * @property mixed decrease_or_increase_coefficient
 * @property integer coins
 * @property mixed invited_at
 * @property mixed created_at
 * @property mixed goal
 * @property mixed protein_ratio
 * @property mixed fat_ratio
 * @property mixed calorie_ratio
 * @property mixed fiber_ratio
 * @property mixed email
 * @property mixed online_at
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone_number', 'online_at', 'height', 'gender', 'birth_date', 'weight', 'activity', 'fat_ratio', 'protein_ratio', 'language', 'decrease_or_increase_coefficient', 'coins', 'invitation_code', 'invited_at', 'invited_by', 'goal_weight', 'email', 'diet_program_period', 'online_at', 'customer', 'nutrition_program_notified_at', 'diet_program_expires_at', 'calorie', 'is_contact',
    ];
    protected $dates = ['online_at', 'nutrition_program_notified_at', 'diet_program_expires_at'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'online_at' => 'datetime',
        'birth_date' => 'datetime',
        'customer' => 'boolean',
        'receive_sms' => 'boolean',
        'is_contact' => 'boolean'
    ];

    protected $appends = ['carbs_ratio', 'age', 'calorie_ratio'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function findForPassport($identifier)
    {
        if (is_numeric($identifier))
            return $this->where('phone_number', $identifier)
                ->orWhere('email', $identifier)
                ->first();
        return null;
    }

    public function userWeight()
    {
        return $this->hasMany(UserWeight::class, 'user_id');
    }

    public function getContactAttribute()
    {
        return empty($this->email) ? $this->phone_number : $this->email;
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function firebase()
    {
        return $this->hasMany(UserFirebaseToken::class, 'user_id')->orderByDesc('id');
    }

    public function getAgeAttribute()
    {
        if ($this->birth_date == null)
            return null;
        return $this->birth_date->diff(Carbon::now())
            ->format('%y');
        // ->format('%y years, %m months and %d days');
    }

    public function unreadMessages()
    {
        return $this->hasMany(ChatMessage::class, 'user_to_id')->whereNull('read_at');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'user_to_id');
    }

    public function getBMRAttribute()
    {
        if ($this->gender == 'male')
            return 88.362 + ($this->weight * 13.397) + ($this->height * 4.799) - ($this->age * 5.677);

        return 447.593 + ($this->weight * 9.247) + ($this->height * 3.098) - ($this->age * 4.33);
    }

    public function getBMIAttribute()
    {
        $heightMeter = $this->height / 100.0;
        return ceil(($this->weight / ($heightMeter * $heightMeter)));
    }

    public function getGenderFaAttribute()
    {
        switch ($this->gender) {
            case 'male':
            default:
                return 'مرد';

            case 'female':
                return 'زن';
        }
    }

    public function getActivityRatioAttribute()
    {
        switch ($this->activity) {
            case 'no_physical_activity':
            default:
                return 1.1;

            case 'sedentary':
                return 1.3;

            case 'somehow_active':
                return 1.5;

            case 'active':
                return 1.7;

            case 'very_active':
                return 1.9;
        }
    }

    public function getActivityFaAttribute()
    {
        switch ($this->activity) {
            case 'no_physical_activity':
            default:
                return 'بدون تحرک فیزیکی';

            case 'sedentary':
                return 'کم تحرک';

            case 'somehow_active':
                return 'به نحوی فعال';

            case 'active':
                return 'فعال';

            case 'very_active':
                return 'خیلی فعال';
        }
    }

    public function getHasFoodSuggestionAttribute()
    {
        $todayProtein = UserFood::whereHas('relation', function ($q) {
            $q->whereDate('date', now())
                ->where('user_id', $this->id);
        })->sum('protein');
        $todayCalorie = UserFood::whereHas('relation', function ($q) {
            $q->whereDate('date', now())
                ->where('user_id', $this->id);
        })->sum(DB::raw('9*fat + 4*protein + 4*carbs'));

        if ($todayCalorie > $this->calorie_ratio)
            return false;

        //$todayCalorie == 0 || $todayCalorie >= $this->calorie_ratio || ($todayCalorie / (double)$this->calorie_ratio) * 100 <= 4
        if ($todayProtein == 0 || $todayProtein >= $this->protein_ratio || ($todayProtein / (double)$this->protein_ratio) * 100 <= 4)
            return false;

        if (!FoodSuggestion::exists())
            return false;

        return
            Carbon::parse('19:20:00') <= now() && now() <= Carbon::parse('23:59:59');
    }

    public function getCalorieRatioAttribute()
    {
        if (!empty($this->calorie))
            return $this->calorie;

        $calorie = $this->BMR * $this->activity_ratio;
        $percentage = $calorie * ($this->decrease_or_increase_coefficient / 100.0);
        return $calorie + $percentage;
    }

    public function getCarbsRatioAttribute()
    {
        return ($this->calorie_ratio - (4 * $this->protein_ratio) - (9 * $this->fat_ratio)) / 4.0;
    }

    public function getProfileCompletedAttribute()
    {
        return !(is_null($this->birth_date) || $this->age <= 5 || is_null($this->weight) || is_null($this->height)
            || is_null($this->gender) || is_null($this->fat_ratio) || is_null($this->protein_ratio)
            || is_null($this->activity) || is_null($this->decrease_or_increase_coefficient)
            || is_null($this->first_name) || is_null($this->last_name)
        );
    }

    public function getCanBeInvitedAttribute(): bool
    {
        return ($this->invited_at == null && now()->addDays(-Constants::$MAX_DAYS_TO_BE_INVITED) < Carbon::parse($this->created_at));
    }

    public function getFiberRatioAttribute()
    {
        return $this->gender == 'male' ? 30 : 25;
    }

    public function getIsAdminAttribute()
    {
        return Admin::where('user_id', $this->id)->exists();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id', 'user_id');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function getOnlineAtShamsiAttribute()
    {
        if ($this->online_at == null)
            return null;

        return Jalalian::fromCarbon($this->online_at)->format('%A, %d %B %Y ساعت H:i');
    }

    public function dietPrograms()
    {
        return $this->hasMany(DietProgram::class, 'user_id');
    }
}
