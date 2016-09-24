<?php

namespace AppBundle\Form;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Form\Common\SiretType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AddCollaboratorType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    
    /**
     * AddressType constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collaborators', EntityType::class, [
                'required' => false,
                'class'    => User::class,
            ]);
        
        $authorizationChecker = $this->authorizationChecker->isGranted(User::ROLE_ADMIN);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($authorizationChecker) {
            /** @var AuthorizationCheckerInterface $authorizationChecker */
            $company = $event->getData();
            $form    = $event->getForm();
            
            if (!$company || null === $company->getId() || $authorizationChecker->isGranted(User::ROLE_ADMIN)) {
                $form
                    ->add('siret', SiretType::class, [
                        'required' => true,
                    ]);
            }
        });
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Company::class,
            'label_format'       => 'form.company.%name%',
            'translation_domain' => 'form',
            'cascade_validation' => true,
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form_company';
    }
}
