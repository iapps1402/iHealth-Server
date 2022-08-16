<?php
namespace App\APIs;

use Illuminate\Support\Facades\Log;

class SendPushNotification
{
    public static $PRIORITY_HIGH = 'high', $PRIORITY_NORMAL = 'normal';
    public static $PUBLIC_TOPIC = 'public';
    private $priority = 'normal',$data;


    public function setPriority($priority){
        $this->priority = $priority;
    }

    public function setData($data){
        $this->data = $data;
    }

    public function sendToRegistrationIds($registrationIds){
        if(empty($registrationIds))
            return;

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
            'registration_ids' => $registrationIds,
            'priority' => $this->priority,
            'data' => $this->data,
        );
        $fields = json_encode($fields);

        $headers = array (
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        $result = json_encode(curl_exec($ch));

        if(isset($result->failure) && $result->failure == 1)
            Log::error('FCM Error => ' . $result);
        curl_close ($ch);
    }
    public function sendToTopic($topic){
        if(empty($topic))
            return;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
            'to' => '/topics/' . $topic,
            'priority' => $this->priority,
            'data' => $this->data
        );
        $fields = json_encode($fields);

        $headers = array (
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt ($ch,CURLOPT_URL, $url);
        curl_setopt ($ch,CURLOPT_POST,true);
        curl_setopt ($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt ($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt ($ch,CURLOPT_POSTFIELDS, $fields);
        $result = json_encode(curl_exec($ch));

        if(isset($result->failure) && $result->failure == 1)
            Log::error('FCM Error => ' . $result->message);
        curl_close ($ch);
    }
}
