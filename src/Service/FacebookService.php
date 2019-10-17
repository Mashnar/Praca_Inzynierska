<?php


namespace App\Service;


use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

/**
 * Class FacebookService
 * Obsługuje Graph API Facebooka
 * @package App\Service
 */
class FacebookService
{
    /** @var $facebook */
    private $facebook;


    /**
     * FacebookService constructor.
     * @throws FacebookSDKException
     */
    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => '802717966793299',
            'app_secret' => 'cf8413336573f8b5ac41aebb8909999d',
            'default_graph_version' => 'v4.0',
            'default_access_token' => 'EAALaEVdDZBlMBAMpsp7v4rW0WnrzeqgPlWlR4pj8kvZACXokZAX0u5bg3wqKZCdo0VJTPOBGCjzAd6ZBRtetxgQAFrrDyaKPyoQ5OrcEmEtZCrlNR0TtkzNYWCVKhc1P6tSj0hQ2yjcCxvs7pPI5ZCqA5czetBXp6VVNGABgsJaLjg9gQ5e3wEu', // optional
        ]);
    }


    /**
     * @return array
     * @throws FacebookSDKException
     */
    public function get(): array
    {

        try {
            // Returns a `FacebookFacebookResponse` object
            $response = $this->facebook->get(
                '/me/feed?fields=message,full_picture,created_time,description,properties&limit=500'
            );
        } catch (FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return $response->getDecodedBody();

    }

}