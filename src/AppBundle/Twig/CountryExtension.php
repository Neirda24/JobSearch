<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;
use Twig_Extension;

class CountryExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('country', array($this, 'countryName')),
        );
    }
    
    public function countryName($countryCode, $locale = null)
    {
        $regionBundle = Intl::getRegionBundle();
        return $regionBundle->getCountryName($countryCode, $locale);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_country_extension';
    }
}
