<?php


namespace App\Service;


use App\Entity\CategoriesWebsite;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class NewsCronService
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

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpclient = $httpClient;


    }

    /**
     * Funkcja wywolywana przez controller, bedziemy tutaj sobie tworzyc tablice z poszczegolnymi kategoriami
     * @return array
     */
    public function newsFromWebsite(): array
    {
        return $this->generateContentToArrayHttp();
    }

    private function generateContentToArrayHttp(): array
    {
        $output = [];
        foreach ($this->slug as $key => $value) {

            $output[$key] = $this->parseReturnContent($this->getPostsByCategory($value));

        }

        return $output;
    }

    /**
     * Funckcja parsujaca jak bedzie wygladac tablica dla wiekszosc typow postów
     * @param array $content
     * @return array
     */
    private function parseReturnContent(array $content): array
    {
        $output = [];
        foreach ($content as $key => $value) {
            $output[] = [
                'title' => $value['title']['rendered'],
                'date' => $value['date'],
                'link' => $value['link'],
                'excerpt' => $value['excerpt']['rendered'],


            ];
        }
        return $output;
    }

    private function getPostsByCategory(int $id_category): array
    {


        //http://wfi.uni.lodz.pl/wp-json/wp/v2/posts?categories=14&per_page=8'
        //zwracam tablice z danymi pod categorie konkretna
        return $this->httpclient->request('GET', $this->url . 'posts?categories=' . $id_category . '&per_page=4')->toArray();


    }

    /**
     *Funkcja bedzie pobierala id kategorii z rest api
     */
    public function generateIdForCategories(): array
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
     * Funkcja tworzaca obiekt klasy categori
     * @param string $slug
     * @param int $id
     * @return CategoriesWebsite
     */
    public function createCategoryObject(string $slug, int $id): CategoriesWebsite
    {
        $object = new CategoriesWebsite();

        $object->setSlug($slug);
        $object->setCategorySlugId($id);

        return $object;

    }


}
