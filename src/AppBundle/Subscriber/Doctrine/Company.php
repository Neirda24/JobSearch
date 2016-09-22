<?php

namespace AppBundle\Subscriber\Doctrine;

use AppBundle\Entity\Company as CompanyEntity;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class Company
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    
    /**
     * Company constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        /** @var CompanyEntity $company */
        $company = $event->getEntity();
    
        if (!$company instanceof CompanyEntity) {
            return;
        }
        
        $user = $this->tokenStorage->getToken()->getUser();
        if (!($user instanceof UserInterface)) {
            throw new AccessDeniedException('You must be authenticated to create a company.');
        }
        
        $company->setSubmittedFrom($user);
    }
}
