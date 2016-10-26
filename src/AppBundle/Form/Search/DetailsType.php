<?php

namespace AppBundle\Form\Search;

use AppBundle\Entity\Company\Collaborator;
use AppBundle\Entity\Search\Details;
use AppBundle\Repository\CollaboratorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collaborators', EntityType::class, [
                'class'         => Collaborator::class,
                'query_builder' => function (CollaboratorRepository $er) {
                    return $er->fetchByCurrentUserQB();
                },
                'required'      => false,
                'expanded'      => false,
                'multiple'      => true,
                'by_reference'  => false,
            ])
            ->add('description', TextareaType::class, [
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
            'data_class'         => Details::class,
            'label_format'       => 'form.search.details.%name%',
            'translation_domain' => 'form',
            'cascade_validation' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form_search_details';
    }
}
