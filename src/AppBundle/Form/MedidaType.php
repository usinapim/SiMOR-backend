<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedidaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ultimoRegistro')
            ->add('variacion')
            ->add('periodo')
            ->add('fechaHora')
            ->add('alerta')
            ->add('evacuacion')
            ->add('fuenteDatos')
            ->add('puerto')
            ->add('estadoRio')

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Medida'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_medida';
    }
}
