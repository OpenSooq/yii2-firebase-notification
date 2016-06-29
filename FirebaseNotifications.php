<?php
namespace opensooq\firebase;

use yii\base\Component;


/**
 * @author Amr Alshroof
 */
class FirebaseNotifications extends Component
{
    /**
     * @var string the api_url for Firebase cloude messageing.
     */
    private $api_url = 'https://fcm.googleapis.com/fcm/send';

   /**
     * @var string the auth_key Firebase cloude messageing server key.
     */
    private $auth_key = 'YOUR_KEY';

   public function __construct($auth_key = '') {
        parent::__construct();
        $this->auth_key = $auth_key;
    }
    

    /**
     * send notification for a specific tokens with FCM
     * @param array $tokens
     * @param string $message
     * @return mixed
     */
    public function send($tokens = [],$message = '')
    {
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
        );

        $headers = array(
            'Authorization:key ='.$this->auth_key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            Yii::error('Curl failed: 'curl_error($ch));
            die;
       }
        curl_close($ch);

        return $result;
    }

}
