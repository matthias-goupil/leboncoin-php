<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Announcement;

class AnnouncementService
{
    private $repository;

    private UserService $serviceUtilisateur;

    public function __construct($repositoryManager, UserService $serviceUtilisateur)
    {
        $this->repository = $repositoryManager->getRepository(Announcement::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
    }

    public function getAll() {
        return $this->repository->findAll();
    }

    public function getAllBy($category, $search, $city) {
        $arraySearch = [];
        if($category != "") $arraySearch["category"] = $category;
        if($city != "") $arraySearch["city"] = $city;

    }

}