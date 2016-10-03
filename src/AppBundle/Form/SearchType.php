<?php

namespace AppBundle\Form;

use AppBundle\Entity\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'widget'   => 'single_text',
                'html5'    => false,
            ])
            ->add('date_end', DateType::class, [
                'required' => false,
                'widget'   => 'single_text',
                'html5'    => false,
            ]);
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
