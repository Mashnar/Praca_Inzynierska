<?php


namespace App\Service;


use App\Entity\CategoriesWebsite;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class CategoriesCronService
{
    /**
     * adres REST API dla strony wydziałowej
     * @var string
     */
    private $url = 'http://wfi.uni.lodz.pl/wp-json/wp/v2/';
    /**
     * @var HttpClientInterface obiekt klasy HttpClientInterface do pobierania danych ze stron
     */
    private $httpclient;

    /**
     * @var array
     * Jest to tablica ze slugami kategori ktore obslugujemy, konsultowalem z adminem strony
     * te bedziemy obslugiwac,bo sa najwazniejsze wedlug mnie
     */
    private $slug = [
        'news-podpiety',
        'news',
        'news-plany',
        'komentarz-ekspercki',


    ];


    private $entityService;


    public function __construct(HttpClientInterface $httpClient, EntityService $entityService)
    {
        $this->httpclient = $httpClient;
        $this->entityService = $entityService;


    }

    /**
     * Funkcja wywolywana przez controller, bedziemy tutaj sobie tworzyc tablice z poszczegolnymi kategoriami
     * @return array
     */
    public function newsFromWebsite(): array
    {
        return $this->generateContentToArrayHttp();
    }






    /**
     *
     */
    public function downloadAndSaveCategories(): void
    {
        /**
         * TODO
         * zrobić walidacje oraz rollback
         */
        // ...
        //zwracam sobie tablice w postaci
        /**
         *
         *
         *  ["news-podpiety"]=>
         * int(27)
         * ["news"]=>
         * int(14)
         * ["news-plany"]=>
         * int(67)
         * ["komentarz-ekspercki"]=>
         * int(205)
         */

        $categories = $this->generateIdForCategories();

        $flush_flag = false;
        //rozpoczynam transakcje
        $this->entityService->beginTransaction();
        foreach ($categories as $slug => $id) {

            if (!$this->checkExistCategoryBySlugAndId($slug, $id)) {
                $flush_flag = true;


                $object = $this->createCategoryObject($slug, $id);

                $this->entityService->persist($object);
            }

        }

        if ($flush_flag) {
            $this->entityService->flush();

            $this->entityService->commit();
        }


    }

    /**
     *Funkcja bedzie pobierala id kategorii z rest api
     */
    private function generateIdForCategories(): array
    {


        $array = $this->getIdForCategory(implode(',', $this->slug));
        foreach ($this->slug as $key => $value) {
            $this->slug[$value] = $array[$key];
        }
        //kasuje pierwsze 4 elementy bo sa juz mi niepotrzebne
        unset($this->slug[0], $this->slug[1], $this->slug[2], $this->slug[3]);


        return $this->slug;

    }

    /**
     * Funkcja pytajaca rest api o id categorii
     * @param string $slug_with_comma
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getIdForCategory(string $slug_with_comma): array
    {
        //mam pewnosc ze bedczie w kolejnosci jakiej zdefiniowalem w tablicy poniewaz orderby iclude slugs
        //https://developer.wordpress.org/rest-api/reference/categories/#list-categories

        $temp = $this->httpclient->request('GET', $this->url . 'categories/?slug=' . $slug_with_comma . '&orderby=include_slugs')->toArray();

        $output = [];
        //przypisuje tylko id bo nic wiecej mi nie optrzebne
        foreach ($temp as $key => $value) {
            $output[] = $value['id'];

        }

        return $output;
    }

    /**
     * Funkcja sprawdzajaca czy istnieje kategoria o danym id  i slugu
     * @param string $slug
     * @param int $id
     * @return bool
     */
    private function checkExistCategoryBySlugAndId(string $slug, int $id): bool
    {
        if ($this->entityService->ifCategoryExistBySlugAndId($id, $slug)) {
            return true;
        }
        return false;

    }

    /**
     * Funkcja tworzaca obiekt klasy categori
     * @param string $slug
     * @param int $id
     * @return CategoriesWebsite
     */
    private function createCategoryObject(string $slug, int $id): CategoriesWebsite
    {
        $object = new CategoriesWebsite();

        $object->setSlug($slug);
        $object->setCategorySlugId($id);

        return $object;

    }


}
