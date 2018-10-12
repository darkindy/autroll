<?php

namespace Darkindy\Autroll;

use Httpful\Request;
use Facebook\Facebook;

/**
 * @author Andrei Pietrușel
 */
class AutrollRunner
{
    const BOT_URL = 'https://www.botlibre.com/rest/json/chat';
    const BOT_APP_ID = '3763306428243137829';
    const BOT_ID = '20873';
    const BOT_LANG = 'romanian';
    const FB_APP_ID = '553075555149656';
    const FB_APP_SECRET = 'c946bdf7225776a2c8024579390be7ed';
    const FB_DEFAULT_ACCESS_TOKEN = 'd96ef89a80f2cd7d4220709216ec143f';
    const FB_GRAPH_V = 'v3.1';

    var $fb = null, $accessToken = null, $helper = null;

    /**
     * AutrollRunner constructor.
     */
    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => self::FB_APP_ID,
            'app_secret' => self::FB_APP_SECRET,
            'default_graph_version' => self::FB_GRAPH_V,
        ]);
        $this->helper = $this->fb->getRedirectLoginHelper();
    }

    public function troll()
    {
        $message = 'care e culoarea ta preferata';
        $response = $this->talkToBot($message);
        echo $response->message . PHP_EOL;
    }

    /**
     * @param $message
     */
    public function talkToBot($message, $conversation = null)
    {
        $request = [
            'application' => self::BOT_APP_ID,
            'instance' => self::BOT_ID,
            'language' => self::BOT_LANG,
            'message' => $message
        ];

        if ($conversation != null) {
            $request->conversation = $conversation;
        }

        $jsonRequest = json_encode($request);

        $jsonResponse = Request::post(self::BOT_URL)
            ->sendsJson()
            ->body($jsonRequest)
            ->send();

        $response = json_decode($jsonResponse);
        return $response;
    }

    public function loginToFacebook()
    {
        try {
            // get the access token
            $this->accessToken = $this->helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            debug_to_console('Graph returned an error: ' . $e->getMessage());
            //exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            debug_to_console('Facebook SDK returned an error: ' . $e->getMessage());
            //exit;
        }

        if($this->accessToken == null) {
            $currentUrl = "http://$_SERVER[HTTP_HOST]"."/autroll/src/index.php";
            $permissions = ['user_friends']; // Optional permissions
            $loginUrl = $this->helper->getLoginUrl($currentUrl, $permissions);

            echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
        } else {
            try {
                // Get the \Facebook\GraphNodes\GraphUser object for the current user.
                // If you provided a 'default_access_token', the '{access-token}' is optional.
                $response = $this->fb->get('/me', $this->accessToken);
                $me = $response->getGraphUser();
                return $me;
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }
        }
        return false;
    }

    public function getFriends() {
        $friends = $this->fb->get('/me/friends', $this->accessToken);
        var_dump($friends->getBody());
    }

    function debug_to_console( $data ) {
        $output = $data;
        if ( is_array( $output ) )
            $output = implode( ',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

}
