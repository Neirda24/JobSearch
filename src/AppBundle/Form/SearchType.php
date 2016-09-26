<?php

namespace AppBundle\Form;

use AppBundle\Entity\Company;
use AppBundle\Entity\Search;
use AppBundle\Form\Common\AddressType;
use AppBundle\Form\Common\SiretType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('date_start', DateType::class, [
                'required' => true,
            ])
            ->add('date_end', DateType::class, [
                'required' => false,
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Search::class,
            'label_format'       => 'form.search.%name%',
            'translation_domain' => 'form',
            'cascade_validation' => true,
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form_search';
    }
}
