<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;
use Twig_Extension;
use Twig_SimpleFilter;

class CountryExtension extends Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('country', [$this, 'countryName']),
        ];
    }
    
    /**
     * @param string $countryCode
     * @param null   $locale
     *
     * @return null|string
     */
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
