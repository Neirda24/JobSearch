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
    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setExtra('translation_domain', 'menu');
        
        // COMPANIES
        $companies = $menu->addChild('company.title', [
            'dropdown' => true,
            'caret'    => true,
        ])->setExtra('translation_domain', 'menu');
        $companies->addChild('company.list', ['route' => 'company_list'])->setExtra('translation_domain', 'menu');
        $companies->addChild('company.create', ['route' => 'company_create'])->setExtra('translation_domain', 'menu');
        
        return $menu;
    }
    
    /**
     * @return ItemInterface
     */
    public function createLoginMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setExtra('translation_domain', 'menu');
        
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('layout.logout', ['route' => 'fos_user_security_logout'])->setExtra('translation_domain', 'FOSUserBundle');
        } else {
            $menu->addChild('layout.register', ['route' => 'fos_user_registration_register'])->setExtra('translation_domain', 'FOSUserBundle');
            $menu->addChild('layout.login', ['route' => 'fos_user_security_login'])->setExtra('translation_domain', 'FOSUserBundle');
        }
        
        return $menu;
    }
}
