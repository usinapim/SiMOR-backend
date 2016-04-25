<?php

namespace UtilBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of R2CollectionType
 *
 * @author santiago.semhan
 */
class VerticalCollectionType extends AbstractType {
    

    public function getParent() {
        return 'bootstrapcollection';
    }

    public function getName() {
        return 'verticalcollection';
    }

}
