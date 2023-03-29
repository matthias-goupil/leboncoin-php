<?php

namespace TheFeed\Business\Services;

use Framework\Storage\Doctrine\RepositoryManagerMySQL;
use TheFeed\Business\Entity\Announcement;
use TheFeed\Business\Entity\Category;
use TheFeed\Business\Entity\User;
use TheFeed\Business\Exception\ServiceException;

class AnnouncementService
{
    private $repository;

    private UserService $serviceUtilisateur;

    private RepositoryManagerMySQL $repositoryManager;
    private string $profilePicturesRoot;

    public function __construct($repositoryManager, UserService $serviceUtilisateur, $profilePicturesRoot)
    {
        $this->repository = $repositoryManager->getRepository(Announcement::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->repositoryManager = $repositoryManager;
        $this->profilePicturesRoot = $profilePicturesRoot;
    }

    public function getAll() {
        return $this->repository->findAll();
    }

    public function getAllBy($category, $search, $city) {
        $arraySearch = [];
        if($category != "") $arraySearch["category"] = $category;
        if($city != "") $arraySearch["city"] = $city;
    }

    /**
     * @throws ServiceException
     */
    public function getAnnouncement($idAnnouncement) {
        $announcement = $this->repository->find($idAnnouncement);
        if($announcement == null){
            throw new ServiceException("Le nom doit être compris entre 3 et 30 caractères!");
        }
        return $announcement;
    }

    /**
     * @throws ServiceException
     */
    public function addToFavorite($idAnnouncement) {
        $announcement = $this->getAnnouncement($idAnnouncement);
        $userId = $this->serviceUtilisateur->getUserId();
        if($userId == null){
            throw new ServiceException("Le nom doit être compris entre 3 et 30 caractères!");
        }
        $user = $this->serviceUtilisateur->getUser($userId);
        $user->addLikedAnnouncement($announcement);
        $this->repositoryManager->persist($user);
        $this->repositoryManager->flush();
    }

    public function removeFromFavorite($idAnnouncement) {

    }

    public function createAnnouncement($name, $description, $picture, $adress, $city, $postalcode, $price, Category $category, User $user) {
        if($name == null || $description == null || $picture == null || $adress == null || $city == null || $postalcode == null|| $price == null || $category == null || $user == null ) {
            throw new ServiceException("Données manquantes!");
        }


        $nameSize = strlen($name);
        if($nameSize < 2 || $nameSize > 180) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }
        $descriptionSize = strlen($description);
        if( $descriptionSize > 2083) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }
        $adressSize = strlen($adress);
        if( $adressSize < 2 && $adressSize > 180) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }
        $citySize = strlen($city);
        if( $citySize < 2 && $citySize > 30) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }

        $fileExtension = $picture->guessExtension();
        if(!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
            throw new ServiceException("La photo de profil n'est pas au bon format!");
        }

        $pictureName = uniqid().'.'.$fileExtension;
        $picture->move($this->profilePicturesRoot, $pictureName);

        $announcement = (new Announcement())
            ->setName($name)
            ->setPicture("/img/annonces/".$pictureName)
            ->setDescription($description)
            ->setPrice($price)
            ->setAdress($adress)
            ->setCity($city)
            ->setPostalcode($postalcode)
            ->setCategory($category)
            ->setAuthor($user);

        $this->repositoryManager->persist($announcement);
        $this->repositoryManager->flush();

        return $announcement;
    }

    public function updateAnnouncement($id, $name, $description, $picture, $adress, $city, $postalcode, $price, Category $category, User $user) {
        if($id == null || $name == null || $description == null || $adress == null || $city == null || $postalcode == null|| $price == null || $category == null || $user == null ) {
            throw new ServiceException("Données manquantes!");
        }


        $nameSize = strlen($name);
        if($nameSize < 2 || $nameSize > 180) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }
        $descriptionSize = strlen($description);
        if( $descriptionSize > 2083) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }
        $adressSize = strlen($adress);
        if( $adressSize < 2 && $adressSize > 180) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }
        $citySize = strlen($city);
        if( $citySize < 2 && $citySize > 30) {
            throw new ServiceException("Le nom de l'annonce doit être compris entre 3 et 180 caractères!");
        }

        $announcement = $this->repository->find($id);
        if($announcement == null){
            throw new ServiceException("Cette annonce n'existe pas");
        }

        $announcement
            ->setName($name)
            ->setDescription($description)
            ->setPrice($price)
            ->setAdress($adress)
            ->setCity($city)
            ->setPostalcode($postalcode)
            ->setCategory($category)
            ->setAuthor($user);

        if($picture != null){
            $fileExtension = $picture->guessExtension();
            if(!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
                throw new ServiceException("La photo de profil n'est pas au bon format!");
            }

            $pictureName = uniqid().'.'.$fileExtension;
            $picture->move($this->profilePicturesRoot, $pictureName);
            $announcement->setPicture("/img/annonces/".$pictureName);
        }

        $this->repositoryManager->persist($announcement);
        $this->repositoryManager->flush();

        return $announcement;
    }

    public function delete($idAnnouncement) {
        $announcement = $this->getAnnouncement($idAnnouncement);
        $this->repositoryManager->remove($announcement);
        $this->repositoryManager->flush();
        echo "oui";
    }
}


