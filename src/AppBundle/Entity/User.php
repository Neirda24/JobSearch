<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
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
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="collaborators")
     */
    private $company;
    
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
    public function hasCompany()
    {
        return ($this->company instanceof Company);
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
    public function setCompany(Company $company = null)
    {
        if (null === $company && $this->company instanceof Company) {
            $company->removeCollaborator($this);
        }
        $this->company = $company;
    }
}
