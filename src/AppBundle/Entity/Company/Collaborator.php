<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Company;
use AppBundle\Entity\Search;
use AppBundle\Entity\Search\Details;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="collaborator")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Collaborator
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
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="collaborators")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="collaboratorsAdded")
     * @ORM\JoinColumn(nullable=false)
     */
    private $addedBy;
    
    /**
     * @var Search[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Search\Details", inversedBy="collaborators")
     */
    private $inSearches;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;
    
    /**
     * Collaborator constructor.
     */
    public function __construct()
    {
        $this->inSearches = new ArrayCollection();
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }
    
    /**
     * @return User
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }
    
    /**
     * @param User $addedBy
     */
    public function setAddedBy(User $addedBy)
    {
        $this->addedBy = $addedBy;
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
     * @return Search[]
     */
    public function getInSearches()
    {
        return $this->inSearches;
    }
    
    /**
     * @param Search[] $inSearches
     */
    public function setInSearches(array $inSearches)
    {
        $this->inSearches = $inSearches;
    }
    
    /**
     * @param Details $searchDetails
     */
    public function addInSearch(Details $searchDetails)
    {
        if (!$this->inSearches->contains($searchDetails)) {
            $this->inSearches->add($searchDetails);
            $searchDetails->addCollaborator($this);
        }
    }
    
    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    
    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = trim($firstname);
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime('now'));
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstname();
    }
}
