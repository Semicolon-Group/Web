<?php

namespace BaseBundle\Form;

use MongoDB\BSON\Timestamp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('content',TextType::class, ['required' => true])->
           // add('file',FileType::class)->

        add('reason',TextType::class, ['required' => true])->
        add('state',HiddenType::class,['data' => 0])->
        add('endDate' , DateType::class, ['required' => true])->

        add('clicks',HiddenType::class,['data' => 0])->
        add('price' ,TextType::class ,
            array('attr' => array(
        'readonly' => true,
                'required' => true

    )))->
        add('payed',HiddenType::class,['data' => 0]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BaseBundle\Entity\Advert',

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'basebundle_advert';
    }


}
