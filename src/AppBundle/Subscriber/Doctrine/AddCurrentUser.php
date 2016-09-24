<?php

namespace AppBundle\Subscriber\Doctrine;

use AppBundle\Entity\Collaborator;
use AppBundle\Entity\Company;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class AddCurrentUser
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    
    /**
     * @var array
     */
    private $mapping = [];
    
    /**
     * Company constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage                 = $tokenStorage;
        $this->mapping[Company::class]      = 'persistCompany';
        $this->mapping[Collaborator::class] = 'persistCollaborator';
    }
    
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        
        if (!array_key_exists(get_class($entity), $this->mapping)) {
            return;
        }
        
        $user = $this->tokenStorage->getToken()->getUser();
        if (!($user instanceof UserInterface)) {
            throw new AccessDeniedException('You must be authenticated to create this object.');
        }
        
        $this->{$this->mapping[get_class($entity)]}($entity, $user);
    }
    
    /**
     * @param Company       $company
     * @param UserInterface $user
     */
    private function persistCompany(Company $company, UserInterface $user)
    {
        $company->setSubmittedFrom($user);
        $company->setOwner($user);
    }
    
    /**
     * @param Collaborator  $collaborator
     * @param UserInterface $user
     */
    private function persistCollaborator(Collaborator $collaborator, UserInterface $user)
    {
        $collaborator->setAddedBy($user);
    }
}
