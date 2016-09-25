<?php

namespace AppBundle\Entity\Search;

use AppBundle\Entity\Company;
use AppBundle\Entity\Company\Collaborator;
use AppBundle\Entity\Search;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="search_details")
 * @ORM\HasLifecycleCallbacks()
 */
class Details
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    
    /**
     * @var Search
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Search", inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     */
    private $search;
    
    /**
     * @var Collaborator[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Company\Collaborator", mappedBy="inSearches")
     */
    private $collaborators;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * Details constructor.
     */
    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Search
     */
    public function getSearch()
    {
        return $this->search;
    }
    
    /**
     * @param Search $search
     */
    public function setSearch(Search $search)
    {
        $this->search = $search;
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
        $this->collaborators = $collaborators;
    }
    
    public function addCollaborator(Collaborator $collaborator)
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
            $collaborator->addInSearch($this);
        }
    }
    
    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    
    /**
     * @return Company[]|ArrayCollection
     */
    public function fetchCompanies()
    {
        return $this->collaborators->map(function (Collaborator $collaborator) {
            return $collaborator->getCompany();
        });
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime('now'));
    }
}
