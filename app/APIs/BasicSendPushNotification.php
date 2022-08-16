<?php

namespace App\APIs;

use App\Models\UserFirebaseToken;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Plokko\Firebase\FCM\Exceptions\FcmErrorException;
use Plokko\Firebase\FCM\Message;
use Plokko\Firebase\FCM\Request;
use Plokko\Firebase\FCM\Targets\Token;
use Plokko\Firebase\ServiceAccount;

class BasicSendPushNotification
{
    public static $PRIORITY_HIGH = 'high', $PRIORITY_NORMAL = 'normal';
    public static $PUBLIC_TOPIC = 'public';
    private $priority = 'normal', $data;
    private $title, $body, $icon;
    private $clickAction = '';

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function sendToRegistrationIds($registrationIds)
    {
        if (empty($registrationIds))
            return;

        foreach ($registrationIds as $id) {
            $message = new Message();
            $message->data->fill($this->data);
            $message->android->data->fill(['android-specific' => 'data']);

            $message->setTarget(new Token($id));
            $sa = new ServiceAccount(storage_path('dopamine-a3d5d-50986713cdee.json'));
            $request = new Request($sa);

            try {
                $message->send($request);

                $message->validate($request);
            } catch (GuzzleException $e) {
                Log::error($e->getMessage());
                return;
            } catch (FcmErrorException $e) {
                switch ($e->getCode()) {
                    case 404:
                        UserFirebaseToken::where('token', $id)->delete();
                        break;

                    default:
                        Log::error($e->getMessage());
                }
                return;
            }
        }
    }

    public function sendToTopic($topic)
    {
        if (empty($topic))
            return;
    }

    public function setClickAction($clickAction)
    {
        $this->clickAction = $clickAction;
    }
}
