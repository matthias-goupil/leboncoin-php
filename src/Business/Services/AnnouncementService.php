<?php

namespace TheFeed\Business\Services;

use Framework\Storage\Doctrine\RepositoryManagerMySQL;
use TheFeed\Business\Entity\Announcement;
use TheFeed\Business\Exception\ServiceException;

class AnnouncementService
{
    private $repository;

    private UserService $serviceUtilisateur;

    private RepositoryManagerMySQL $repositoryManager;

    public function __construct($repositoryManager, UserService $serviceUtilisateur)
    {
        $this->repository = $repositoryManager->getRepository(Announcement::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->repositoryManager = $repositoryManager;
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

    public function search($category = null, $search = null, $city = null)
    {
        $qb = $this->repository->createQueryBuilder('a');

        if ($category !== null && $category !== '') {
            $qb->andWhere('a.category = :category')
                ->setParameter('category', $category);
        }

        if ($search !== null && $search !== '') {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('a.name', ':search'),
                $qb->expr()->like('a.description', ':search')
            ))
                ->setParameter('search', '%' . $search . '%');
        }

        if ($city !== null && $city !== '') {
            $qb->andWhere('a.city = :city')
                ->setParameter('city', $city);
        }

        // Ajout d'une condition pour retourner toutes les annonces si aucun paramètre n'est renseigné
        if ($category === null && ($search === null || $search === '') && ($city === null || $city === '')) {
            return $this->repository->findAll();
        }

        return $qb->getQuery()->getResult();
    }



}