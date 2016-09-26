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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CollaboratorRepository")
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
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @var Search[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Search\Details", inversedBy="collaborators")
     */
    private $searchDetails;
    
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
        $this->searchDetails = new ArrayCollection();
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
    public function getSearchDetails()
    {
        return $this->searchDetails;
    }
    
    /**
     * @param Search[] $searchDetails
     */
    public function setSearchDetails(array $searchDetails)
    {
        $this->searchDetails = $searchDetails;
    }
    
    /**
     * @param Details $searchDetails
     */
    public function addSearchDetails(Details $searchDetails)
    {
        if (!$this->searchDetails->contains($searchDetails)) {
            $this->searchDetails->add($searchDetails);
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
