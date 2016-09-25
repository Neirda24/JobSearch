<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Common\Address;
use AppBundle\Entity\Common\Siret;
use AppBundle\Entity\Company\Collaborator;
use AppBundle\Entity\Search\Details;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use SLLH\IsoCodesValidator\Constraints as IsoCodesAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="company",
 *     uniqueConstraints={
 *          @UniqueConstraint(
 *              name="UNIQUE_COMPANY_SIRET",
 *              columns={
 *                  "siret_siren", "siret_nic"
 *              }
 *          )
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 * @UniqueEntity(fields={"siret.siren", "siret.nic"})
 * @ORM\HasLifecycleCallbacks()
 */
class Company
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @var Address
     *
     * @ORM\Embedded(class="AppBundle\Entity\Common\Address")
     * @Assert\Valid(traverse=true)
     */
    private $address;
    
    /**
     * @var Siret
     *
     * @ORM\Embedded(class="AppBundle\Entity\Common\Siret")
     * @Assert\Valid(traverse=true)
     */
    private $siret;
    
    /**
     * @var Collaborator[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company\Collaborator", mappedBy="company")
     * @Assert\Valid(traverse=true)
     */
    private $collaborators;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="submittedCompanies")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(traverse=true)
     */
    private $submittedFrom;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="companiesOwned")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(traverse=true)
     */
    private $owner;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
        $this->siret         = new Siret();
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = trim($name);
    }
    
    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }
    
    /**
     * @return Siret
     */
    public function getSiret()
    {
        return $this->siret;
    }
    
    /**
     * @param Siret $siret
     */
    public function setSiret(Siret $siret)
    {
        $this->siret = $siret;
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
    
    /**
     * @param Collaborator $collaborator
     *
     * @return bool
     */
    public function hasCollaborator(Collaborator $collaborator)
    {
        return $this->collaborators->contains($collaborator);
    }
    
    /**
     * @param Collaborator $collaborator
     */
    public function addCollaborator(Collaborator $collaborator)
    {
        if (!$this->hasCollaborator($collaborator)) {
            $this->collaborators->add($collaborator);
        }
    }
    
    /**
     * @param Collaborator $collaborator
     */
    public function removeCollaborator(Collaborator $collaborator)
    {
        if ($this->hasCollaborator($collaborator)) {
            $this->collaborators->removeElement($collaborator);
        }
    }
    
    /**
     * @return User
     */
    public function getSubmittedFrom()
    {
        return $this->submittedFrom;
    }
    
    /**
     * @param User $submittedFrom
     */
    public function setSubmittedFrom(User $submittedFrom)
    {
        $this->submittedFrom = $submittedFrom;
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
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }
    
    /**
     * @param User $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }
    
    /**
     * @param User|null $user
     *
     * @return bool|null
     */
    public function isOwner(User $user = null)
    {
        if (null !== $user && null !== $this->owner) {
            return ($this->owner->getId() === $user->getId());
        }
        
        return null;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime('now'));
    }
}
