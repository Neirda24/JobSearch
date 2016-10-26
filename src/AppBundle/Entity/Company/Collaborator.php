<?php

namespace AppBundle\Entity\Company;

use AppBundle\Entity\Search;
use AppBundle\Entity\Search\Details;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use LogicException;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Traversable;

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
     * @var PhoneNumber
     *
     * @ORM\Column(type="phone_number", nullable=true)
     *
     * @AssertPhoneNumber(defaultRegion="FR", regionProperty="country")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="submittedCollaborators")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(traverse=true)
     */
    private $addedBy;

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
     *
     * @throws LogicException
     */
    public function setSearchDetails($searchDetails)
    {
        if (!is_array($searchDetails) && !$searchDetails instanceof Traversable) {
            throw new LogicException(sprintf('Expected array or traversable got [%s]', get_class($searchDetails)));
        }
        if ($searchDetails instanceof Collection) {
            $searchDetails = $searchDetails->toArray();
        }

        array_walk($searchDetails, [$this, 'addSearchDetails']);
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
     * @return boolean
     */
    public function hasFirstname()
    {
        return (null !== $this->firstname);
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
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return boolean
     */
    public function hasLastname()
    {
        return (null !== $this->lastname);
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
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $result = '';

        if ($this->hasFirstname()) {
            $result .= $this->getFirstname();
        }

        if ($this->hasFirstname() && $this->hasLastname()) {
            $result .= ' ';
        }

        if ($this->hasLastname()) {
            $result .= $this->getLastname();
        }

        return $result;
    }

    /**
     * @return boolean
     */
    public function hasEmail()
    {
        return (null !== $this->email);
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
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function hasPhone()
    {
        return (null !== $this->phone);
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
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
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
     * @param User|null $user
     *
     * @return bool|null
     */
    public function wasAddedBy(User $user = null)
    {
        if (null !== $user && null !== $this->addedBy) {
            return ($this->addedBy->getId() === $user->getId());
        }

        return false;
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
