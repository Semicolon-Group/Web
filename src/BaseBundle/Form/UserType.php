<?php

namespace BaseBundle\Form;

use BaseBundle\Entity\Enumerations\BodyType;
use BaseBundle\Entity\Enumerations\Categorie;
use BaseBundle\Entity\Enumerations\CivilStatus;
use BaseBundle\Entity\Enumerations\Gender;
use BaseBundle\Entity\Enumerations\Importance;
use BaseBundle\Entity\Enumerations\PriceRange;
use BaseBundle\Entity\Enumerations\RelationType;
use BaseBundle\Entity\Enumerations\Religion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker', 'placeholder' => 'Birthday'],
            ])
            ->add('gender', ChoiceType::class, array(
                'choices' => array('Gender' => null) + Gender::getEnumAsArray(),
                'choice_attr' => array(
                    'Gender' => array('selected' => 'selected', 'disabled' => true)
                )
            ))
            ->add('height')
            ->add('bodyType', ChoiceType::class, array(
                'choices' => array('Body Type' => null) + BodyType::getEnumAsArray(),
                'choice_attr' => array(
                    'Body Type' => array('selected' => 'selected', 'disabled' => true)
                )
            ))
            ->add('childrenNumber')
            ->add('relegion', ChoiceType::class, array(
                'choices' => array('Religion' => null)+Religion::getEnumAsArray(),
                'choice_attr' => array(
                    'Religion' => array('selected' => 'selected', 'disabled' => true)
                )
            ))
            ->add('relegionImportance', ChoiceType::class, array(
                'choices' => array('Religion Importance' => null) + Importance::getEnumAsArray(),
                'choice_attr' => array(
                    'Religion Importance' => array('selected' => 'selected', 'disabled' => true)
                )
            ))
            ->add('smoker')
            ->add('drinker')
            ->add('minAge')
            ->add('maxAge')
            ->add('phone')
            ->add('locked', HiddenType::class, array(
                'data' => 0
            ))
            ->add('createdAt', DateTimeType::class, array(
                'data' => new \DateTime(),
                'attr' => array('style' => 'display: none;'),
                'label' => false
            ))
            ->add('about')
            ->add('civilStatus', ChoiceType::class, array(
                'choices' => array('Marital Status' => null) + CivilStatus::getEnumAsArray(),
                'choice_attr' => array(
                    'Marital Status' => array('selected' => 'selected', 'disabled' => true)
                )
            ))
            ->add('preferedStatuses', ChoiceType::class, array(
                'choices' => CivilStatus::getEnumAsArray(),
                'multiple' => true,
                'expanded' => true
            ))
            ->add('address', AddressType::class, array(
                'label' => false
            ))
            ->add('preferedRelations', ChoiceType::class, array(
                'choices' => RelationType::getEnumAsArray(),
                'multiple' => true,
                'expanded' => true)
            )
            ->add('category', ChoiceType::class, array(
                    'choices' => array('Category' => null) + Categorie::getEnumAsArray(),
                    'choice_attr' => array(
                        'Category' => array('selected' => 'selected', 'disabled' => true)
                    ))
                )
            ->add('priceRange', ChoiceType::class, array(
                    'choices' => array('Price Range' => null) + PriceRange::getEnumAsArray(),
                    'choice_attr' => array(
                        'Price Range' => array('selected' => 'selected', 'disabled' => true)
                    ))
                )
            ->add('link')
            ->add('roles');
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
