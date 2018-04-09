<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 4/8/2018
 * Time: 1:30 PM
 */

namespace BaseBundle\Form;

use BaseBundle\Entity\Enumerations\BodyType;
use BaseBundle\Entity\Enumerations\CivilStatus;
use BaseBundle\Entity\Enumerations\Gender;
use BaseBundle\Entity\Enumerations\Importance;
use BaseBundle\Entity\Enumerations\RelationType;
use BaseBundle\Entity\Enumerations\Religion;
use BaseBundle\Entity\PreferedStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
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
                    'Gender' => array('disabled' => true)
                )
            ))
            ->add('height')
            ->add('bodyType', ChoiceType::class, array(
                'choices' => array('Body Type' => null) + BodyType::getEnumAsArray(),
                'choice_attr' => array(
                    'Body Type' => array('disabled' => true)
                )
            ))
            ->add('childrenNumber')
            ->add('relegion', ChoiceType::class, array(
                'choices' => array('Religion' => null)+Religion::getEnumAsArray(),
                'choice_attr' => array(
                    'Religion' => array('disabled' => true)
                )
            ))
            ->add('relegionImportance', ChoiceType::class, array(
                'choices' => array('Religion Importance' => null) + Importance::getEnumAsArray(),
                'choice_attr' => array(
                    'Religion Importance' => array('disabled' => true)
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
            ->add('civilStatus', ChoiceType::class, array(
                'choices' => array('Marital Status' => null) + CivilStatus::getEnumAsArray(),
                'choice_attr' => array(
                    'Marital Status' => array('selected' => 'selected', 'disabled' => true)
                )
            ))
            ->add('address', AddressType::class, array(
                'label' => false
            ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
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