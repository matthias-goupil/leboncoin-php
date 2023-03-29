<?php

namespace TheFeed\Business\Services;

use Framework\Services\UserSessionManager;
use TheFeed\Business\Entity\User;
use TheFeed\Business\Exception\ServiceException;

class UserService
{
    private $repository;

    private UserSessionManager $sessionManager;

    private $secretSeed;

    private $profilePicturesRoot;

    private $repositoryManager;

    public function __construct($repositoryManager, $sessionManager, $secretSeed, $profilePicturesRoot)
    {
        $this->repository = $repositoryManager->getRepository(User::class);
        $this->repositoryManager = $repositoryManager;
        $this->sessionManager = $sessionManager;
        $this->secretSeed = $secretSeed;
        $this->profilePicturesRoot = $profilePicturesRoot;
    }

    public function getUser($userId, $allowNull = true) {
        $user = $this->repository->find($userId);
        if(!$allowNull && $user == null) {
            throw new ServiceException("Utilisateur inexistant!");
        }
        return $user;
    }

    public function getUserByEmail($email, $allowNull = true) {
        $user =  $this->repository->findOneBy([
            "email" => $email
        ]);
        if(!$allowNull && $user == null) {
            throw new ServiceException("Utilisateur inexistant!");
        }
        return $user;
    }

    public function createUser($email, $firstname, $lastname, $password, $tel) {

        if($email == null || $password == null || $firstname == null || $lastname == null || $tel == null) {
            throw new ServiceException("Données manquantes!");
        }
        $firstnameSize = strlen($firstname);
        if($firstnameSize < 2 || $firstnameSize > 30) {
            throw new ServiceException("Le prénom doit être compris entre 3 et 30 caractères!");
        }

        $lastnameSize = strlen($lastname);
        if($lastnameSize < 4 || $lastnameSize > 30) {
            throw new ServiceException("Le nom doit être compris entre 3 et 30 caractères!");
        }
        if(!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#", $password)) {
            throw new ServiceException("Mot de passe invalide!");
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("L'adresse mail est incorrecte!");
        }
        echo" oiu";

        $user = $this->repository->findOneBy([
            "email" => $email
        ]);
        echo" oiu";

        if($user != null) {
            throw new ServiceException("Ce login est déjà pris!");
        }

//        $fileExtension = $picUploadedFile->guessExtension();
//        if(!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
//            throw new ServiceException("La photo de profil n'est pas au bon format!");
//        }

//        $pictureName = uniqid().'.'.$fileExtension;
//        $picUploadedFile->move($this->profilePicturesRoot, $pictureName);
        $passwordChiffre = password_hash($this->secretSeed.$password, PASSWORD_BCRYPT);
        $user = (new User())
            ->setEmail($email)
            ->setLastname($lastname)
            ->setFirstname($firstname)
            ->setPassword($passwordChiffre)
            ->setTel($tel);

        $this->repositoryManager->persist($user);
        $this->repositoryManager->flush();
    }

    public function login($email, $passwordClair) {
        $user = $this->repository->findOneBy(["email" => $email]);
        if($user != null) {
            $password = $this->secretSeed.$passwordClair;
            if(password_verify($password, $user->getPassword())) {
                $this->sessionManager->set("id", $user->getId());
                return;
            }
        }
        throw new ServiceException("L'adresse email et/ou le mot de passe sont erronés");
    }

    public function updateConnectedUser( $firstname, $lastname, $password, $tel) {
        if($this->estConnecte()) {
            if ($firstname == null || $lastname == null || $tel == null) {
                throw new ServiceException("Données manquantes!");
            }
            $firstnameSize = strlen($firstname);
            if ($firstnameSize < 2 || $firstnameSize > 30) {
                throw new ServiceException("Le prénom doit être compris entre 3 et 30 caractères!");
            }

            $lastnameSize = strlen($lastname);
            if ($lastnameSize < 4 || $lastnameSize > 30) {
                throw new ServiceException("Le nom doit être compris entre 3 et 30 caractères!");
            }

//        $fileExtension = $picUploadedFile->guessExtension();
//        if(!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
//            throw new ServiceException("La photo de profil n'est pas au bon format!");
//        }

//        $pictureName = uniqid().'.'.$fileExtension;
//        $picUploadedFile->move($this->profilePicturesRoot, $pictureName);
            $user = $this->getUser($this->getUserId());
            $user
                ->setLastname($lastname)
                ->setFirstname($firstname)
                ->setTel($tel);
            if ($password != null) {
                if (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#", $password)) {
                    throw new ServiceException("Mot de passe invalide!");
                }
                $passwordChiffre = password_hash($this->secretSeed . $password, PASSWORD_BCRYPT);
                $user->setPassword($passwordChiffre);
            }

            $this->repositoryManager->persist($user);
            $this->repositoryManager->flush();
        }
    }

    public function getUserId() {
        return $this->sessionManager->get('id');
    }

    public function logout() {
        $this->sessionManager->remove('id');
    }

    public function estConnecte() {
        return $this->sessionManager->has('id');
    }
}