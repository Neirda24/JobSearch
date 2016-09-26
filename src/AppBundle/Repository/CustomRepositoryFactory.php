<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CustomRepositoryFactory implements RepositoryFactory
{
    /**
     * The list of EntityRepository instances.
     *
     * @var ObjectRepository[]
     */
    private $repositoryList = [];
    
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    
    /**
     * CustomRepositoryFactory constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repositoryHash = $entityManager->getClassMetadata($entityName)->getName() . spl_object_hash($entityManager);
        
        if (!isset($this->repositoryList[$repositoryHash])) {
            $this->repositoryList[$repositoryHash] = $this->createRepository($entityManager, $entityName);
        }
        
        return $this->repositoryList[$repositoryHash];
    }
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param                        $entityName
     *
     * @return ObjectRepository
     */
    private function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        /* @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
        $metadata            = $entityManager->getClassMetadata($entityName);
        $repositoryClassName = $metadata->customRepositoryClassName
            ?: $entityManager->getConfiguration()->getDefaultRepositoryClassName();
        
        $repository = new $repositoryClassName($entityManager, $metadata);
        switch (true) {
            case ($repository instanceof TokenStorageAwareRepository):
                $repository->setTokenStorage($this->tokenStorage);
            default:
                break;
        }
        
        return $repository;
    }
}
