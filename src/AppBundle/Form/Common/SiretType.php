<?php

namespace AppBundle\Form\Common;

use AppBundle\Entity\Common\Siret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiretType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siren', IntegerType::class, [
                'required' => true,
            ])
            ->add('nic', IntegerType::class, [
                'required' => true,
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Siret::class,
            'label_format'       => 'form.siret.%name%',
            'translation_domain' => 'form',
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form_common_siret';
    }
}
