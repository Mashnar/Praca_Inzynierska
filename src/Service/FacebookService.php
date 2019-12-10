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
            'app_id' => '2387146028203414',
            'app_secret' => 'fc7bb324994ecf5cae4a074288000f6f',
            'default_graph_version' => 'v4.0',
            'default_access_token' => 'EAAh7GL8JvZAYBADHsGZB1iu7UIrk0fifnlluX6ZAs7NvQyqzQCYu8jwaMB1Ej0uIMZBCp1ImHb4ZAvtWBaF6VuF9epP2eelDBHUYf7SA8SpTmlZCjO5ZAMLscZAZCBauqlvbxoLpIhCZCnqbT6JsLcciRR6OVenRKBBsTZADhFwfz6zNhThqwiX4yK4ZBH2cNH5HLGEZD', // optional
        ]);
        //
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
                '/me/feed?fields=message,full_picture,created_time,description,properties&limit=20'
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