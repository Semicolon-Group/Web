<?php

namespace BaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextareaType::class)
            ->add('title', TextareaType::class, ['required' => true])
            ->add('photoUrl',FileType::class, array('data_class' => null))
            ->add('maxPlaces', NumberType::class, array('attr' => ['min' => 0]))
            ->add('startDate',DateTimeType::class, ['required' => true], array(
            'data' => new \DateTime(),
            'attr' => array('style' => 'display: yes;'),
            'label' => false
        ))
            ->add('state', ChoiceType::class,[
                'choices' => [ 'Approved' => '1',  'Not processed' => '0', 'Denied'=>'2' ]
            ])
            ->add('reason',TextareaType::class, ['required' => true])
            ->add('endDate',DateType::class, ['required' => true])
            ->add('address', AddressType::class, array(
                'label' => false))
            ->add('valider',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BaseBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'basebundle_event';
    }


}
