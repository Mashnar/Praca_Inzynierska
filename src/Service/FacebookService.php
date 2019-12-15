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
            'app_id' => '429144981296142',
            'app_secret' => '7f1e8f65ec3783214a93e12f2bca8fa4',
            'default_graph_version' => 'v4.0',
            'default_access_token' => 'EAAGGThoq1A4BAMkgrNWaLuQdjLPOqBQ3Sqs0eMiqEY6bQm2Rcndgf0svU9H66GwZBZARRPG4QdFoCStkwUn1kg5nib940ThD9Nj3QWs2702XKDadwgwYzzwj3Ofrfr3RmfqA3ZCJZCZApFcLqSlsZC6MlRT5sGnMT80yAlJh4xLq79KkZCD9H8APL0FI11zsI4ZD', // optional
        ]);
        //EAAGGThoq1A4BADgg4ZA2RJZBWZCM2Unq7CZAqvyPDcvjJdsMIH9ZCaofWz8RAuSlrZAFW88mZAFT5yFapwFb4zJEYavn93uyPl0ZCg42zYfNAdkquFYFYNXgHgGXew4eINIZBXQXevxBPsZCyA2HBCkYwDU33mfQ7hfIsffW48z2T3FeZCdJI62fktaJeWllrMXc3Ys11ZBMWA5OHuC6m0ZAoBPUcAPt6p6Tbo8MZD
        //EAAGGThoq1A4BAAfkaPTJTKjqUJ7623nDFHLklvsk5qwGLZAyqNDBC1DMcw67MomZCaaebmTgASCheBnZC3XneknHpWIi8K8zY67qVcBVaHjVPbFoBEcj928iZB4ZASSCAje5x3GElTQUJWKcZA1rWksGoLUAENoO43p3QNZA8YdqhOmyobk7buZB
        //2708594142509335
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
                '/me/events'
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