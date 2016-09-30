<?php

namespace AppBundle\Form\Company;

use AppBundle\Entity\Company;
use AppBundle\Entity\Search;
use AppBundle\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListCompanyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $search = $options['search'];
        
        $builder
            ->add('company', EntityType::class, [
                'required' => true,
                'class'    => Company::class,
                'multiple' => false,
                'expanded' => false,
                'group_by' => function(Company $company) {
                    return Intl::getRegionBundle()->getCountryName($company->getAddress()->getCountry());
                },
                'query_builder' => function (CompanyRepository $er) use ($search) {
                    return $er->excludeFromSearchQB($search);
                },
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('search', null)
            ->setRequired('search')
            ->setAllowedTypes('search', Search::class)
        ;
        $resolver->setDefault('translation_domain', 'form');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form_company_list';
    }
}
