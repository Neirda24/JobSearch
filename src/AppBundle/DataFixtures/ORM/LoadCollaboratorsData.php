<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Company\Collaborator;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCollaboratorsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $collaborators = [
            'olivier'   => [
                'company' => 'company-mireille',
                'addedBy' => 'user-neirda24',
                'searches' => [
                    'detail-after_iron-1',
                    'detail-after_phileas-2',
                ],
            ],
            'alexandre' => [
                'company' => 'company-google',
                'addedBy' => 'user-neirda',
                'searches' => [
                    'detail-after_iron-0',
                    'detail-after_phileas-1',
                    'detail-after_phileas-0',
                ],
            ],
        ];
        
        foreach ($collaborators as $firstname => $config) {
            $collaborator = new Collaborator();
            $collaborator->setAddedBy($this->getReference($config['addedBy']));
            $collaborator->setCompany($this->getReference($config['company']));
            $collaborator->setFirstname($firstname);
            
            foreach ($config['searches'] as $searchReference) {
                $collaborator->addInSearch($this->getReference($searchReference));
            }
            
            $manager->persist($collaborator);
            
            $this->addReference('collaborator-' . $firstname, $collaborator);
        }
        $manager->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
