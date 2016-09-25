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
                'details'   => 3,
            ],
            'after_iron'    => [
                'dateStart' => '-2 month',
                'dateEnd'   => '-1 month',
                'owner'     => 'user-neirda',
                'details'   => 2
            ],
        ];
        
        foreach ($searches as $name => $config) {
            $search = new Search();
            $search->setOwner($this->getReference($config['owner']));
            $search->setName($name);
            $search->setDateStart(new DateTime($config['dateStart']));
            $search->setDateEnd(new DateTime($config['dateEnd']));
            
            for ($i = 0; $i < $config['details']; $i++) {
                $detail = new Details();
                $detail->setSearch($search);
                $this->addReference('detail-' . $name . '-' . $i, $detail);
                
                $manager->persist($detail);
            }
            
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
