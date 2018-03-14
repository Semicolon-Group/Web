<?php

namespace BaseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('importance')
            ->add('date')
            ->add('selectedChoice', EntityType::class, [
                'class' => 'BaseBundle\Entity\Choice',
                'choice_label' => 'choice',
                'multiple' => false
            ])
            ->add('question', EntityType::class, [
                'class' => 'BaseBundle\Entity\Question',
                'choice_label' => 'question',
                'multiple' => false
            ])
            ->add('user', EntityType::class, [
                'class' => 'BaseBundle\Entity\User',
                'choice_label' => 'firstname',
                'multiple' => false
            ])
            ->add('choice', EntityType::class, [
                'class' => 'BaseBundle\Entity\Choice',
                'choice_label' => 'choice',
                'multiple' => true
            ])
            ->add('add', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BaseBundle\Entity\Answer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'basebundle_answer';
    }


}
