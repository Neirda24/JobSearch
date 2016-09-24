<?php

namespace AppBundle\Entity;

use DateTime;
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="collaborators")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $verifiedAt;
    
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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * @return DateTime
     */
    public function getVerifiedAt()
    {
        return $this->verifiedAt;
    }
    
    /**
     * @param DateTime $verifiedAt
     */
    public function setVerifiedAt(DateTime $verifiedAt)
    {
        $this->verifiedAt = $verifiedAt;
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
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime('now'));
    }
}
