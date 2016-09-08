<?php

namespace AppBundle\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use SLLH\IsoCodesValidator\Constraints as IsoCodesAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Embeddable
 */
class Siret
{
    /**
     * @ORM\Column(type="integer")
     *
     * @IsoCodesAssert\Siren
     *
     * @var int
     */
    private $siren;
    
    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Length(
     *     max="5",
     *     maxMessage="error.siret.nic.max_length"
     * )
     *
     * @var int
     */
    private $nic;
    
    /**
     * @return string
     */
    public function getSiren()
    {
        if ('' !== trim($this->siren)) {
            return sprintf('%09d', $this->siren);
        }
        
        return null;
    }
    
    /**
     * @return string
     */
    public function getFormattedSiren()
    {
        return chunk_split($this->getSiren(), 3);
    }
    
    /**
     * @param int $siren
     */
    public function setSiren(int $siren)
    {
        $this->siren = $siren;
    }
    
    /**
     * @return string
     */
    public function getNic()
    {
        if ('' !== trim($this->nic)) {
            return sprintf('%05d', $this->nic);
        }
        
        return null;
    }
    
    /**
     * @param int $nic
     */
    public function setNic(int $nic)
    {
        $this->nic = $nic;
    }
    
    /**
     * @return string
     */
    public function __toString(): string
    {
        return trim(trim($this->getFormattedSiren()) . ' ' . trim($this->getNic()));
    }
}
