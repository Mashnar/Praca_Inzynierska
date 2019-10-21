<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    /** @var ValidatorInterface */
    private $validator;


    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }


    /** Funkcja walidująca nasz obiekt
     * @param $object
     * @return bool|Response
     */
    public function validate($object): ?string
    {
        //Walidujemy nasz obiekt
        $errors = $this->validator->validate($object);
        //Jesli sa errory, zwracamy echo i konczymy dzialanie skryptu
        if (count($errors) > 0) {


            $response = new Response((string)($errors), Response::HTTP_NOT_ACCEPTABLE);
            $response->send();
            die;


        }
        return true;
    }


    /**Funkcja sprawdza nazwe zmiennej device_name, jesli nie istnieje, to zwracamy false, jesli istnieje true
     * @param $data
     * @return bool
     */
    public function checkVariableName(array $data): bool
    {
        //Jesli nie istnieje taki klucz w tablicy, to zwracamy false ze nie ma odpowiedniego pola
        if (!(array_key_exists('device_name', $data))) {
            return false;
        }
        return true;
    }



}