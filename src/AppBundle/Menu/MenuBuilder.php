<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;
    
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    
    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory              = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }
    
    /**
     * @return ItemInterface
     */
    public function createLoginMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('dashboard.title', ['route' => 'dashboard'])->setAttribute('icon', 'fa fa-list');
            $menu->addChild('layout.logout', ['route' => 'fos_user_security_logout'])->setExtra('translation_domain', 'FOSUserBundle');
        } else {
            $menu->addChild('layout.register', ['route' => 'fos_user_registration_register'])->setExtra('translation_domain', 'FOSUserBundle');
            $menu->addChild('layout.login', ['route' => 'fos_user_security_login'])
                ->setExtra('translation_domain', 'FOSUserBundle')
                ->setAttribute('icon', 'fa fa-user');
        }
        
        return $menu;
    }
}
