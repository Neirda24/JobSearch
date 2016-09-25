<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Search\Details;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="search")
 */
class Search
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
     * @ORM\Column(type="string", length=40)
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *     max="40"
     * )
     */
    private $name;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\Date()
     */
    private $dateStart;
    
    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $dateEnd;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="searches")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Valid(traverse=true)
     */
    private $owner;
    
    /**
     * @var Details[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Search\Details", mappedBy="search")
     */
    private $details;
    
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
     * @return DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }
    
    /**
     * @param DateTime $dateStart
     */
    public function setDateStart(DateTime $dateStart)
    {
        $this->dateStart = $dateStart;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }
    
    /**
     * @param DateTime|null $dateEnd
     */
    public function setDateEnd(DateTime $dateEnd = null)
    {
        $this->dateEnd = $dateEnd;
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
     * @return bool
     */
    public function isOwner(User $user = null)
    {
        if (null !== $user) {
            return ($user->getId() === $this->owner->getId());
        }
        
        return false;
    }
    
    /**
     * @return Details[]
     */
    public function getDetails()
    {
        return $this->details;
    }
    
    /**
     * @param Details[] $details
     */
    public function setDetails(array $details)
    {
        $this->details = $details;
    }
    
    /**
     * @return ArrayCollection[]|ArrayCollection
     */
    public function fetchGroupedDetails()
    {
        $groupedDetails = new ArrayCollection();
        
        foreach ($this->details as $detail) {
            foreach ($detail->getCollaborators() as $collaborator) {
                if (!$groupedDetails->containsKey($collaborator->getCompany()->getId())) {
                    $groupedDetails->set($collaborator->getCompany()->getId(), new ArrayCollection());
                }
                if (!$groupedDetails->get($collaborator->getCompany()->getId())->containsKey($detail->getId())) {
                    $groupedDetails->get($collaborator->getCompany()->getId())->set($detail->getId(), $detail);
                }
            }
        }
        
        return $groupedDetails;
    }
    
    /**
     * @return ArrayCollection|ArrayCollection[]
     */
    public function fetchGroupedOrderedDetails()
    {
        $groupedDetails = $this->fetchGroupedDetails();
        
        foreach ($groupedDetails as $groupedDetail) {
            uasort($groupedDetail, function (Details $details1, Details $details2) {
                if ($details1->getCreatedAt() === $details2->getCreatedAt()) {
                    return 0;
                }
    
                return ($details1->getCreatedAt() < $details2->getCreatedAt()) ? -1 : 1;
            });
        }
        
        uasort($groupedDetails, function (ArrayCollection $details1, ArrayCollection $details2) {
            if ($details1->first()->getCreatedAt() === $details2->first()->getCreatedAt()) {
                return 0;
            }
    
            return ($details1->first()->getCreatedAt() < $details2->first()->getCreatedAt()) ? -1 : 1;
        });
        
        return $groupedDetails;
    }
    
    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param string                    $payload
     */
    public function validateDateEnd(ExecutionContextInterface $context, $payload)
    {
        if (null !== $this->dateEnd && $this->dateStart < $this->dateEnd) {
            $context->buildViolation('The date end should be greater than date start.')
                ->atPath('dateEnd')
                ->addViolation();
        }
    }
}
