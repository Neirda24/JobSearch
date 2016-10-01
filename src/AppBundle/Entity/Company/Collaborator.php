<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Search;
use AppBundle\Entity\Search\Details;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Email(checkHost=true, checkMX=true, strict="true")
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;
    
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
        $this->firstname = $firstname;
    }
    
    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    
    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    
    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime('now'));
    }
    
    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param                           $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $fieldsToCheck = [
            'firstname',
            'lastname'
        ];
        
        foreach ($fieldsToCheck as $fieldToCheck) {
            $value = $this->$fieldToCheck;
            
            if ('' !== trim($value)) {
                return;
            }
        }
        
        $context->buildViolation('The collaborator should have at least a firstname or a lastname.')
            ->atPath('firstName')
            ->addViolation();
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        $result = '';
        if (null !== $this->getFirstname()) {
            $result .= $this->getFirstname();
        }
        
        if (null !== $this->getLastname()) {
            if ('' !== trim($result)) {
                $result .= ' ';
            }
            
            $result .= $this->getLastname();
        }
        
        if ('' !== trim($this->getEmail()) || '' !== trim($this->getPhone())) {
            if ('' !== trim($result)) {
                $result .= ' <';
            }
            
            if ('' !== trim($this->getEmail())) {
                $result .= $this->getEmail();
            }
            
            if ('' !== trim($this->getPhone())) {
                if ('' !== trim($this->getEmail())) {
                    $result .= ', ';
                }
                $result .= $this->getPhone();
            }
            
            if ('' !== trim($result)) {
                $result .= '>';
            }
        }
        
        return $result;
    }
}
