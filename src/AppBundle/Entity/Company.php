<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Common\Address;
use AppBundle\Entity\Common\Siret;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use SLLH\IsoCodesValidator\Constraints as IsoCodesAssert;

/**
 * @ORM\Table(
 *     name="company",
 *     uniqueConstraints={
 *          @UniqueConstraint(
 *              name="UNIQUE_COMPANY_SIRET",
 *              columns={
 *                  "siret_siren","siret_nic"
 *              }
 *          )
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;
    
    /**
     * @ORM\Embedded(class="AppBundle\Entity\Common\Address")
     * @Assert\Valid(traverse=true)
     *
     * @var Address
     */
    private $address;
    
    /**
     * @ORM\Embedded(class="AppBundle\Entity\Common\Siret")
     * @Assert\Valid(traverse=true)
     *
     * @var Siret
     */
    private $siret;
    
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
}
