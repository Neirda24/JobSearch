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
                'searches' => [
                    'detail-after_iron-2',
                    'detail-after_phileas-3',
                ],
            ],
            'alexandre' => [
                'searches' => [
                    'detail-after_iron-1',
                    'detail-after_phileas-2',
                    'detail-after_phileas-1',
                ],
            ],
        ];

        foreach ($collaborators as $firstname => $config) {
            $collaborator = new Collaborator();
            $collaborator->setCountry('FR');
            $collaborator->setFirstname($firstname);
            $collaborator->setAddedBy($this->getReference('user-neirda24'));

            foreach ($config['searches'] as $searchReference) {
                $collaborator->addSearchDetails($this->getReference($searchReference));
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
