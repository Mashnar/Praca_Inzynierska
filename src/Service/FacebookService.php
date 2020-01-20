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
     * @var $content array tablica trzymajaca wszystkie posty z graph api
     */
    private $content;

    /**
     * FacebookService constructor.
     * @throws FacebookSDKException
     */
    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => '982456528778869',
            'app_secret' => 'e77e17e2df56b2d34a3aec1e378c5173',
            'default_graph_version' => 'v4.0',
            'default_access_token' => 'EAAN9ifxntnUBACJaDmfMcHJzHiQJK6z8aMzUg60F1h5zNOqv9bDXbPKo26eU17ZB1fj02MiULUbZB3VSTOjynaql3T7sSBAe7vepAU9nvpIhqlAnIufCJwdNSL8KEoMmXZCJg2piobM6Aa2EnsI9ZC48ZCaoDliuc28c1Gzp1ppQDGU0TfMDbz3RvtkOgad8ZD', // optional
        ]);
        //GOTOWY KLUCZ DOSTĘPU
        //EAAN9ifxntnUBACJaDmfMcHJzHiQJK6z8aMzUg60F1h5zNOqv9bDXbPKo26eU17ZB1fj02MiULUbZB3VSTOjynaql3T7sSBAe7vepAU9nvpIhqlAnIufCJwdNSL8KEoMmXZCJg2piobM6Aa2EnsI9ZC48ZCaoDliuc28c1Gzp1ppQDGU0TfMDbz3RvtkOgad8ZD
    }


    /**
     * Funkcja zwracajcaca tablice z wiadomoscia,obrazkiem,czasem stworzenia,pelnym obrazkiem i linkiem (limit 8)
     * @return array
     * @throws FacebookSDKException
     */
    public function get(): array
    {

        try {

            $response = $this->facebook->get(
                'me/posts?fields=message,picture,created_time,full_picture,actions&limit=8'
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


    /**
     * Funkcja zwracajaca tablice z ostatnimi 8 newsami
     * @return array
     * @throws FacebookSDKException
     */
    public function getFacebookNews(): array
    {
        $this->content = $this->get();

        //kasuje paginacje, nie potrzebuje
        $this->cleanupArray();
        return $this->content;

    }

    /**
     * //tablica przed
     *array:2 [▼
     * "data" => array:8 [▼
     * 0 => array:6 [▼
     * "message" => """
     * Serdecznie dziękujemy wszystkim, którzy włączyli się w tegoroczną zbiórkę na rzecz Szlachetna PACZKA 🎁🎁🎁 Dzięki Waszej pomocy Pan Andrzej otrzymał prezent, k ▶
     *
     * DZIĘKUJEMY!!!
     * """
     * "picture" => "https://scontent.xx.fbcdn.net/v/t1.0-0/s130x130/78844737_1514291148724918_8301446062543994880_n.jpg?_nc_cat=103&_nc_ohc=fIBFPqOVAA8AQmj-5UE8P0VPeWX0GOpAnLct_45_ ▶"
     * "created_time" => "2019-12-09T14:11:47+0000"
     * "full_picture" => "https://scontent.xx.fbcdn.net/v/t1.0-9/s720x720/78844737_1514291148724918_8301446062543994880_n.jpg?_nc_cat=103&_nc_ohc=fIBFPqOVAA8AQmj-5UE8P0VPeWX0GOpAnLct_45_ ▶"
     * "actions" => array:1 [▶]
     * "id" => "198291450269964_2535880789844340"
     * ]
     * 1 => array:6 [▶]
     * 2 => array:6 [▶]
     * 3 => array:6 [▶]
     * 4 => array:6 [▶]
     * 5 => array:6 [▶]
     * 6 => array:6 [▶]
     * 7 => array:6 [▶]
     * ]
     * "paging" => array:2 [▼
     * "cursors" => array:2 [▶]
     * "next" => "https://graph.facebook.com/v5.0/198291450269964/posts?access_token=EAAN9ifxntnUBACJaDmfMcHJzHiQJK6z8aMzUg60F1h5zNOqv9bDXbPKo26eU17ZB1fj02MiULUbZB3VSTOjynaql3T7s ▶"
     * ]
     * ]
     *
     *
     *
     *  PO :
     * array:1 [▼
     * "data" => array:8 [▼
     * 0 => array:6 [▼
     * "message" => """
     * Serdecznie dziękujemy wszystkim, którzy włączyli się w tegoroczną zbiórkę na rzecz Szlachetna PACZKA 🎁🎁🎁 Dzięki Waszej pomocy Pan Andrzej otrzymał prezent, k ▶
     *
     * DZIĘKUJEMY!!!
     * """
     * "picture" => "https://scontent.xx.fbcdn.net/v/t1.0-0/s130x130/78844737_1514291148724918_8301446062543994880_n.jpg?_nc_cat=103&_nc_ohc=fIBFPqOVAA8AQmj-5UE8P0VPeWX0GOpAnLct_45_ ▶"
     * "created_time" => "2019-12-09T14:11:47+0000"
     * "full_picture" => "https://scontent.xx.fbcdn.net/v/t1.0-9/s720x720/78844737_1514291148724918_8301446062543994880_n.jpg?_nc_cat=103&_nc_ohc=fIBFPqOVAA8AQmj-5UE8P0VPeWX0GOpAnLct_45_ ▶"
     * "id" => "198291450269964_2535880789844340"
     * "link" => "https://www.facebook.com/198291450269964/posts/2535880789844340/"
     * ]
     * 1 => array:6 [▶]
     * 2 => array:6 [▶]
     * 3 => array:6 [▶]
     * 4 => array:6 [▶]
     * 5 => array:6 [▶]
     * 6 => array:6 [▶]
     * 7 => array:6 [▶]
     * ]
     * ]
     *Funkcja czyszcząca tablice( przenosze link na główny element oraz kasuje actions)
     */
    private function cleanupArray(): void
    {
        //kasuje element paginacji
        unset($this->content['paging']);
        //foreach po data
        foreach ($this->content as $value) {
            //foreach po kazdym poscie
            foreach ($value as $key => $data) {


                //przenosze link na górny element tablicy
                $this->content['data'][$key]['link'] = $data['actions'][0]['link'];
                //kasuje element z akcja

                unset($this->content['data'][$key]['actions']);
            }
        }

    }


}