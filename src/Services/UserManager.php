<?php


namespace App\Services;


use App\Entity\User;
use App\Helper\Utils;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $entityManager;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Create a new user in the database
     * @param $requestParam
     * @return array
     * @throws UniqueConstraintViolationException
     */
    public function newUser($requestParam)
    {
        $request = $requestParam->all();

        if (!isset($request["firstname"]) || !isset($request["lastname"]) || !isset($request["email"]) || !isset($request["phone_number"])
            || !isset($request["password"])) {
            return Utils::setMessage(400, "Invalid parameters");
        }

        if (!filter_var($request["email"], FILTER_VALIDATE_EMAIL)) {
            return Utils::setMessage(400, "Please enter a email valid");
        }

        if (strlen($request["phone_number"]) > 10 || strlen($request["phone_number"]) < 10) {
            return Utils::setMessage(400, "Please enter a phone number valid");
        }

        $checkUser = $this->entityManager->find(User::class)->findByEmail($request["email"]);

        if (!empty($checkUser)) {
            return Utils::setMessage(402, "User already exist");
        }

        return $this->createUser($request);
    }

    /**
     * Add new user in the database
     * @param $newUser
     *
     * @return array
     * @throws UniqueConstraintViolationException
     */
    private function createUser($newUser)
    {
        $encodedPassword = password_hash($newUser["password"], PASSWORD_DEFAULT);

        $user = new User;
        $user->setFirstName($newUser["firstname"]);
        $user->setLastName($newUser["lastname"]);
        $user->setPassword($encodedPassword);
        $user->setEmail($newUser["email"]);
        $user->setPhoneNumber($newUser["phone"]);

        $this->entityManager->persist($user);

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            return Utils::setMessage(500, "Something wrong : " . $exception->getMessage());
        }

        return Utils::setMessage(201, "User created");
    }
}