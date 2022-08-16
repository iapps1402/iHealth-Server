<?php


namespace App\Helpers;


use App\Models\Adjustment;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;

class HelperAdjustment
{
    private static function getValue($key)
    {
        $adjustment = Setting::where('key', $key)->first();
        return $adjustment == null ? null : $adjustment->value;
    }

    private static function setValue($key, $value): void
    {
        Setting::updateOrCreate([
            'key' => $key,
        ], [
            'value' => $value
        ]);
    }


    public static function setEmail($value): void
    {
        self::setValue('email', $value);
    }

    public static function getEmail(): ?string
    {
        $value = self::getValue('email');
        return $value == null ? null : $value;
    }

    public static function setTelegramNotificationChannelId($value): void
    {
        self::setValue('telegram_notification_channel_id', $value);
    }

    public static function getTelegramNotificationChannelId(): ?string
    {
        $value = self::getValue('telegram_notification_channel_id');
        return $value == null ? null : $value;
    }
}
