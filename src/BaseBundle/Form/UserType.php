<?php

namespace BaseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname')
            ->add('lastname')
            ->add('birthDate')
            ->add('gender')
            ->add('height')
            ->add('bodyType')
            ->add('childrenNumber')
            ->add('relegion')
            ->add('relegionImportance')
            ->add('smoker')
            ->add('drinker')
            ->add('minAge')
            ->add('maxAge')
            ->add('phone')
            ->add('locked')
            ->add('ip')
            ->add('port')
            ->add('role')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('about')
            ->add('civilStatus')
            ->add('connected')
            ->add('category')
            ->add('priceRange')
            ->add('link');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BaseBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'basebundle_user';
    }


}
