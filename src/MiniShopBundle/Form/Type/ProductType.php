<?php

namespace MiniShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProductType
 * @package MiniShop\Form\Type
 */
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('description', 'textarea')
            ->add('price', 'money')
            ->add('photos', 'collection', array(
                'label' => false,
                'type'   => new PhotoType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'cascade_validation' => true,
                'by_reference' => false,
                'options' => array(
                    'required' => true
                ),
                'label_render' => false
            ))
            ->add('save', 'submit', array('label' => 'Save product'));
    }

    public function getName()
    {
        return 'product';
    }
}