<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserFirebaseToken
 *
 * @method static updateOrCreate(array $array, array $array1)
 * @method static create(array $array)
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $platform
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RestaurantAdmin $restaurantManagers
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFirebaseToken whereUserId($value)
 */
class UserFirebaseToken extends Model
{
    protected $table = 'user_firebase_tokens';
    protected $fillable = ['user_id', 'token'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
