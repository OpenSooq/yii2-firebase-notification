<?php
namespace opensooq\firebase;

use yii\base\BaseObject;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @author Amr Alshroof
 */
class FirebaseNotifications extends BaseObject
{
    /**
     * @var string the auth_key Firebase cloude messageing server key.
     */
    public $authKey;

    public $timeout = 5;
    public $sslVerifyHost = false;
    public $sslVerifyPeer = false;

    /**
     * @var string the api_url for Firebase cloude messageing.
     */
    public $apiUrl = 'https://fcm.googleapis.com/fcm/send';

    public function init()
    {
        if (!$this->authKey) throw new \Exception("Empty authKey");
    }
    
    /**
     * send raw body to FCM
     * @param array $body
     * @return mixed
     */
    public function send($body)
    {
        $headers = [
            "Authorization:key={$this->authKey}",
            'Content-Type: application/json',
            'Expect: ',
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_SSL_VERIFYHOST => $this->sslVerifyHost,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerifyPeer,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FRESH_CONNECT  => false,
            CURLOPT_FORBID_REUSE   => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_POSTFIELDS     => json_encode($body),
        ]);
        $result = curl_exec($ch);
        if ($result === false) {
            Yii::error('Curl failed: '.curl_error($ch).", with result=$result");
            throw new \Exception("Could not send notification");
        }
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code<200 || $code>=300) {
            Yii::error("got unexpected response code $code with result=$result");
            throw new \Exception("Could not send notification");
        }
        curl_close($ch);
        $result = json_decode($result , true);
        return $result;
    }

    /**
     * high level method to send notification for a specific tokens (registration_ids) with FCM
     * see https://firebase.google.com/docs/cloud-messaging/http-server-ref
     * see https://firebase.google.com/docs/cloud-messaging/concept-options#notifications_and_data_messages
     * 
     * @param array  $tokens the registration ids
     * @param array  $notification can be something like {title:, body:, sound:, badge:, click_action:, }
     * @param array  $options other FCM options https://firebase.google.com/docs/cloud-messaging/http-server-ref#downstream-http-messages-json
     * @return mixed
     */
    public function sendNotification($tokens = [], $notification, $options = [])
    {
        $body = [
            'registration_ids' => $tokens,
            'notification' => $notification,
        ];
        $body = ArrayHelper::merge($body, $options);
        return $this->send($body);
    }

}
