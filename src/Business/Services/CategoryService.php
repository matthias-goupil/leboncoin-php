<?php

namespace TheFeed\Business\Services;

use Framework\Storage\Doctrine\RepositoryManagerMySQL;
use TheFeed\Business\Entity\Category;
use TheFeed\Business\Exception\ServiceException;


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

    public function getCategoryByName($name) {
        return $this->repository->findOneBy(["name" => $name]);
    }

    public function createCategoryIfDontExists($name): Category{
        $category = $this->getCategoryByName($name);
        if($category) return $category;
        $category = (new Category())
            ->setName($name);
        $this->repositoryManager->persist($category);
        $this->repositoryManager->flush();
        return $category;
    }
}