<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Category;
use Framework\Storage\Doctrine\RepositoryManagerMySQL;

class CategoryService
{
    private $repository;

    private UserService $serviceUtilisateur;

    private RepositoryManagerMySQL $repositoryManager;

    public function __construct($repositoryManager, UserService $serviceUtilisateur)
    {
        $this->repository = $repositoryManager->getRepository(Category::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->repositoryManager = $repositoryManager;
    }

    public function getCategories() {
        return $this->repository->findAll();
    }

}