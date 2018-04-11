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
            ->add('title', TextareaType::class)
            ->add('maxPlaces')
            ->add('startDate',DateTimeType::class, array(
            'data' => new \DateTime(),
            'attr' => array('style' => 'display: yes;'),
            'label' => false
        ))
            ->add('state',choiceType::class ,[
                    'choices' => [ 'Approved' => '1',  'Not processed' => '0', 'Denied'=>'2' ]
                ]
            )
            ->add('reason',TextareaType::class)
            ->add('endDate',DateType::class)
            ->add('address', AddressType::class, array(
                'label' => false));
            if(!$options['edit']){
                $builder->add('photoUrl',FileType::class, array('data_class' => null));
            }
            $builder->add('valider',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BaseBundle\Entity\Event',
            'edit' => false
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
