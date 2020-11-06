<?php


namespace App\Controller;


use App\Helper\Utils;
use App\Services\UserManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;

class UserController
{

    public function register(Request $request, UserManager $manager)
    {
        try {
            $data = $manager->newUser($request->request);
        } catch (UniqueConstraintViolationException $e) {
            return Utils::setDataResponse(409, "Un Utilisateur avec cette email existe déjà", false);
        }

        return Utils::setDataResponse($data["code"], $data["message"]);
    }
}