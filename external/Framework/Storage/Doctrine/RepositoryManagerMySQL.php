<?php
namespace Framework\Storage\Doctrine;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

class RepositoryManagerMySQL
{
    private EntityManager $entityManager;

    public function __construct($dataBaseConfig)
    {
        $config = $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(__DIR__."/../../../../src/Business/Entity"),
            isDevMode: true,
        );

        $connectionParams = [
            'dbname' => $dataBaseConfig['database'],
            'user' => $dataBaseConfig['login'],
            'password' => $dataBaseConfig['password'],
            'host' => $dataBaseConfig['hostname'],
            'driver' => 'pdo_mysql',
        ];

        $connection = DriverManager::getConnection($connectionParams);

        $this->entityManager = new EntityManager($connection, $config);
    }

    public function persist($entity) {
        $this->entityManager->persist($entity);
    }

    public function flush() {
        $this->entityManager->flush();
    }

    public function remove($entity) {
       $this->entityManager->remove($entity);
    }

    public function bin() {
        ConsoleRunner::run(
            new SingleManagerProvider($this->entityManager)
        );
    }

    public function getRepository(string $name): \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
    {
        return $this->entityManager->getRepository($name);
    }
}