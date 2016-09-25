<?php

namespace AppBundle\Subscriber\Doctrine;

use AppBundle\Entity\Company;
use AppBundle\Entity\Company\Collaborator;
use AppBundle\Entity\Search;
use AppBundle\Entity\User;
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
        $this->mapping[Search::class]       = 'persistSearch';
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
        
        $token = $this->tokenStorage->getToken();
        $user  = null;
        if (null !== $token) {
            $user = $token->getUser();
        }
        
        $this->{$this->mapping[get_class($entity)]}($entity, $user);
    }
    
    /**
     * @param UserInterface|null $user
     *
     * @throws AccessDeniedException
     */
    private function denyAccessUnlessConnected(UserInterface $user = null)
    {
        if (null === $user) {
            throw new AccessDeniedException('You must be authenticated to create this object.');
        }
    }
    
    /**
     * @param Company       $company
     * @param UserInterface $user
     */
    private function persistCompany(Company $company, UserInterface $user = null)
    {
        if (!$company->getSubmittedFrom() instanceof User || !$company->getOwner() instanceof User) {
            $this->denyAccessUnlessConnected($user);
            $company->setSubmittedFrom($user);
            $company->setOwner($user);
        }
    }
    
    /**
     * @param Collaborator  $collaborator
     * @param UserInterface $user
     */
    private function persistCollaborator(Collaborator $collaborator, UserInterface $user = null)
    {
        if (!$collaborator->getAddedBy() instanceof User) {
            $this->denyAccessUnlessConnected($user);
            $collaborator->setAddedBy($user);
        }
    }
    
    /**
     * @param Search        $search
     * @param UserInterface $user
     */
    private function persistSearch(Search $search, UserInterface $user = null)
    {
        if (!$search->getOwner() instanceof User) {
            $this->denyAccessUnlessConnected($user);
            $search->setOwner($user);
        }
    }
}
