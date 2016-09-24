<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Company\Collaborator;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';
    
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validatedAt;
    
    /**
     * @var Collaborator[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Collaborator", mappedBy="addedBy")
     * @ORM\JoinColumn(nullable=true)
     */
    private $collaboratorsAdded;
    
    /**
     * @var Company[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company", mappedBy="submittedFrom")
     * @ORM\JoinColumn(nullable=true)
     */
    private $submittedCompanies;
    
    /**
     * @var Company[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company", mappedBy="owner")
     * @ORM\JoinColumn(nullable=true)
     */
    private $companiesOwned;
    
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->collaborators      = new ArrayCollection();
        $this->submittedCompanies = new ArrayCollection();
        $this->companiesOwned     = new ArrayCollection();
    }
    
    /**
     * @return DateTime
     */
    public function getValidatedAt()
    {
        return $this->validatedAt;
    }
    
    /**
     * @param DateTime $validatedAt
     */
    public function setValidatedAt(DateTime $validatedAt)
    {
        $this->validatedAt = $validatedAt;
    }
    
    /**
     * @return bool
     */
    public function hasCollaborator()
    {
        return !$this->collaborators->isEmpty();
    }
    
    /**
     * @return Collaborator[]
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }
    
    /**
     * @param Collaborator[] $collaborators
     */
    public function setCollaborators(array $collaborators)
    {
        array_walk($collaborators, [$this, 'addCollaborator']);
    }
    
    /**
     * @param Collaborator $collaborator
     */
    public function addCollaborator(Collaborator $collaborator)
    {
        $this->collaborators->add($collaborator);
    }
    
    /**
     * @return Company[]
     */
    public function getSubmittedCompanies()
    {
        return $this->submittedCompanies;
    }
    
    /**
     * @param Company[] $submittedCompanies
     */
    public function setSubmittedCompanies(array $submittedCompanies)
    {
        $this->submittedCompanies = $submittedCompanies;
    }
    
    /**
     * @param Company $company
     *
     * @return bool
     */
    public function hasSubmittedCompany(Company $company)
    {
        return $this->submittedCompanies->contains($company);
    }
    
    /**
     * @param Company $submittedCompany
     */
    public function addSubmittedCompany(Company $submittedCompany)
    {
        if (!$this->hasSubmittedCompany($submittedCompany)) {
            $this->submittedCompanies->add($submittedCompany);
        }
    }
    
    /**
     * @return Collaborator[]
     */
    public function getCollaboratorsAdded()
    {
        return $this->collaboratorsAdded;
    }
    
    /**
     * @param Collaborator[] $collaboratorsAdded
     */
    public function setCollaboratorsAdded(array $collaboratorsAdded)
    {
        $this->collaboratorsAdded = $collaboratorsAdded;
    }
    
    /**
     * @return Company[]
     */
    public function getCompaniesOwned()
    {
        return $this->companiesOwned;
    }
    
    /**
     * @param Company[] $companiesOwned
     */
    public function setCompaniesOwned(array $companiesOwned)
    {
        $this->companiesOwned = $companiesOwned;
    }
}
