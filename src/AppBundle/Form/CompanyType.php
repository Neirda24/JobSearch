<?php

namespace AppBundle\Form;

use AppBundle\Entity\Company;
use AppBundle\Form\Common\AddressType;
use AppBundle\Form\Common\SiretType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CompanyType extends AbstractType
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
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('address', AddressType::class, [
                'required' => false,
            ])
        ;

        $authorizationChecker = $this->authorizationChecker;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($authorizationChecker) {
            $company = $event->getData();
            $form    = $event->getForm();

            if (!$company || null === $company->getId() || $authorizationChecker->isGranted('ROLE_ADMIN') || $authorizationChecker->isGranted('OWNER', $company)) {
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
