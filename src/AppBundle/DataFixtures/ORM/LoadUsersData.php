<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $users = [
            'neirda24' => [
                'email' => 'testplop_neirda24@yopmail.com',
            ],
            'neirda' => [
                'email' => 'testplop_neirda@yopmail.com',
            ],
        ];
        
        $encoder = $this->container->get('security.password_encoder');
        foreach ($users as $userName => $config) {
            $user = new User();
            $user->setUsername($userName);
            $user->setUsernameCanonical($userName);
            
            $password = $encoder->encodePassword($user, 'plop');
            $user->setPassword($password);
            $user->setEnabled(true);
            $user->setEmail($config['email']);
            $user->setEmailCanonical($config['email']);
            
            $manager->persist($user);
            
            $this->addReference('user-' . $userName, $user);
        }
        $manager->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
