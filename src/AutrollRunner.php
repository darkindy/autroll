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
    const FB_GRAPH_V = 'v2.10';

    public function troll()
    {
        $message = 'care e culoarea ta preferata';
        $response = $this->talkToBot($message);
        echo $response->message . PHP_EOL;

        $fb = new Facebook([
            'app_id' => self::FB_APP_ID,
            'app_secret' => self::FB_APP_SECRET,
            'default_graph_version' => self::FB_GRAPH_V,
            'default_access_token' => self::FB_DEFAULT_ACCESS_TOKEN,
        ]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', '{access-token}');
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();
        echo 'Logged in as ' . $me->getName();
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

}
