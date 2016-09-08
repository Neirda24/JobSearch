<?php

namespace AppBundle\Entity\Common;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use ZipCodeValidator\Constraints\ZipCode;

/**
 * @Embeddable
 */
class Address
{
    /**
     * @Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $streetNumber;
    
    /**
     * @Column(type="string", nullable=true)
     *
     * @var string
     */
    private $street;
    
    /**
     * @Column(type="string", nullable=true)
     *
     * @ZipCode(getter="getCountry", message="error.address.zipcode")
     *
     * @var string
     */
    private $postalCode;
    
    /**
     * @Column(type="string", nullable=true)
     *
     * @var string
     */
    private $city;
    
    /**
     * @Column(type="string", nullable=true)
     *
     * @var string
     */
    private $country;
    
    /**
     * @return int
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }
    
    /**
     * @param integer $streetNumber
     */
    public function setStreetNumber(int $streetNumber = null)
    {
        $this->streetNumber = $streetNumber;
    }
    
    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * @param string $city
     */
    public function setCity(string $city = null)
    {
        if (null !== $city) {
            $city = trim($city);
        }
        
        $this->city = $city;
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
    public function setCountry(string $country = null)
    {
        if (null !== $country) {
            $country = trim($country);
        }
        
        $this->country = $country;
    }
    
    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }
    
    /**
     * @param string $street
     */
    public function setStreet(string $street = null)
    {
        if (null !== $street) {
            $street = trim($street);
        }
        
        $this->street = $street;
    }
    
    /**
     * @return string
     */
    public function getPostalCode()
    {
        if ('' !== trim($this->postalCode)) {
            return sprintf('%05d', $this->postalCode);
        }
        
        return $this->postalCode;
    }
    
    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode = null)
    {
        if (null !== $postalCode) {
            $postalCode = trim($postalCode);
        }
        
        $this->postalCode = $postalCode;
    }
    
    /**
     * @return string
     */
    public function __toString(): string
    {
        $address = '';
        
        if ('' !== trim($this->street)) {
            if (0 < $this->streetNumber) {
                $address = $this->streetNumber . ' ';
            }
            
            $address .= trim($this->street);
        }
    
        if ('' !== trim($this->getPostalCode())) {
            if ('' !== $address) {
                $address .= ', ';
            }
        
            $address .= $this->getPostalCode();
        }
    
        if ('' !== trim($this->city)) {
            if ('' !== trim($address)) {
                $address .= ' ';
            }
        
            $address .= trim($this->city);
        }
    
        if ('' !== trim($this->country)) {
            if ('' !== $address) {
                $address .= ', ';
            }
        
            $address .= $this->country;
        }
        
        return $address;
    }
}
