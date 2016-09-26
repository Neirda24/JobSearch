<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Search;
use AppBundle\Entity\Search\Details;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSearchData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $searches = [
            'after_phileas' => [
                'dateStart' => '-1 month',
                'dateEnd'   => 'now',
                'owner'     => 'user-neirda24',
                'details'   => [
                    ['company' => 'company-google', 'description' => 'skype first interview'],
                    ['company' => 'company-mireille', 'description' => 'by call. offer made'],
                    ['company' => 'company-google', 'description' => 'offer made'],
                ],
            ],
            'after_iron'    => [
                'dateStart' => '-2 month',
                'dateEnd'   => '-1 month',
                'owner'     => 'user-neirda',
                'details'   => [
                    ['company' => 'company-mireille', 'description' => 'by call. offer made'],
                    ['company' => 'company-google', 'description' => 'offer made'],
                ],
            ],
        ];
        
        foreach ($searches as $name => $config) {
            $search = new Search();
            $search->setOwner($this->getReference($config['owner']));
            $search->setName($name);
            $search->setDateStart(new DateTime($config['dateStart']));
            $search->setDateEnd(new DateTime($config['dateEnd']));
            
            $i = 0;
            foreach ($config['details'] as $detailConfig) {
                $i++;
                $detail = new Details();
                $detail->setSearch($search);
                $detail->setCompany($this->getReference($detailConfig['company']));
                $detail->setDescription($detailConfig['description']);
                $this->addReference('detail-' . $name . '-' . $i, $detail);
                
                $manager->persist($detail);
            }
            unset($i);
            
            $manager->persist($search);
            
            $this->addReference('search-' . $name, $search);
        }
        $manager->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
