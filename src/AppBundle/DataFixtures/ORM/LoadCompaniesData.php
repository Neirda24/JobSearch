<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Common\Address;
use AppBundle\Entity\Common\Siret;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCompaniesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $companies = [
            'mireille' => [
                'siren' => 789722345,
                'nic' => 12,
                'user' => 'user-neirda24',
            ],
            'google' => [
                'siren' => 803970128,
                'nic' => 10,
                'user' => 'user-neirda',
            ],
        ];
        
        foreach ($companies as $companyName => $companyConfig) {
            $company = new Company();
            $company->setSubmittedFrom($this->getReference($companyConfig['user']));
            $company->setOwner($company->getSubmittedFrom());
            $company->setName($companyName);
            
            $address = new Address();
            $address->setCountry('FR');
            
            $company->setAddress($address);
            
            $siret = new Siret();
            $siret->setNic($companyConfig['nic']);
            $siret->setSiren($companyConfig['siren']);
            
            $company->setSiret($siret);
            
            $manager->persist($company);
            
            $this->addReference('company-' . $companyName, $company);
        }
        $manager->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
