<?php

namespace AppBundle\Validator\Constraint;

use IsoCodes;
use SLLH\IsoCodesValidator\AbstractConstraint;
use SLLH\IsoCodesValidator\Constraints\ZipCode as Base;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class ZipCode extends AbstractConstraint
{
    const ALL = 'all';
    
    public $country = self::ALL;
    
    public $message = 'This value is not a valid ZIP code.';
    
    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        if (true === array_key_exists('country', $options)) {
            $this->country = $options['country'];
        }
        
        if ($this->country !== self::ALL && !in_array($this->country, IsoCodes\ZipCode::getAvailableCountries(), true)) {
            throw new ConstraintDefinitionException(sprintf(
                'The option "country" must be one of "%s" or "all". %s given',
                implode('", "', IsoCodes\ZipCode::getAvailableCountries()),
                $this->country
            ));
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIsoCodesVersion()
    {
        return '1.0.0';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIsoCodesClass()
    {
        return IsoCodes\ZipCode::class;
    }
}
