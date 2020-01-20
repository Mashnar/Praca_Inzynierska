<?php


namespace App\Service;


use App\Entity\CategoriesWebsite;
use App\Entity\WebsitePosts;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsCronService
{
    /**
     * adres REST API dla strony wydziałowej ( do pobierania postów)
     * @var string
     */
    private $url = 'http://wfi.uni.lodz.pl/wp-json/wp/v2/posts';

    /**
     * @var HttpClientInterface obiekt klasy HttpClientInterface do pobierania danych ze stron
     */
    private $httpclient;
    private $entityService;

    public function __construct(HttpClientInterface $httpClient, EntityService $entityService)
    {
        $this->httpclient = $httpClient;
        $this->entityService = $entityService;

    }


    /**
     *
     */
    public function downloadAndSaveNews(): void
    {
        //czyszcze tabele

        $this->entityService->clearTable(WebsitePosts::class);
        $this->entityService->beginTransaction();
        //pobieram wszystkie kategorie
        $categories = $this->getCategories();

        foreach ($categories as $obj) {

            //zbieram posty dla danego categoryid
            $posts = $this->getPostsByCategoryId($obj->getCategorySlugId());

            //serializuje tablice i wrzucam do obiektu
            $this->entityService->persist($this->createObject($obj, $posts));


        }

        $this->entityService->flush();
        $this->entityService->commit();
    }


    private function createObject(CategoriesWebsite $categoryWebsite, array $posts): WebsitePosts
    {
        $obj = new WebsitePosts();
        $obj->setPosts($posts);
        $obj->setCategoryId($categoryWebsite);

        return $obj;

    }


    /**
     * Funkcja zwracajaca wszystkie kategorie ze strony widzlaowej
     * @return array
     */
    private function getCategories(): array
    {

        return $this->entityService->getAllCategories();

    }


    /**
     * Funkcja pobieraja kategorie dla danego castegory id
     * //http://wfi.uni.lodz.pl/wp-json/wp/v2/posts?categories=27&per_page=4
     * @param int $categoryId
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getPostsByCategoryId(int $categoryId): array
    {

        //http://wfi.uni.lodz.pl/wp-json/wp/v2/posts?categories=14&per_page=8'
        //zwracam tablice z danymi pod categorie konkretna
        return $this->parseReturnContent(
            $this->httpclient->request('GET', $this->url . '?categories=' . $categoryId . '&per_page=4')->toArray());

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


}