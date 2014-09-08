<?php

namespace LifeList\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TodoType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
		->add('name')
		->add('tags', null, array(
		    "type" => "text",
		    "allow_add" => true,
		    "allow_delete" => true
		))
		->add('completed')
	;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
	$resolver->setDefaults(array(
	    'data_class' => 'LifeList\ApiBundle\Entity\Todo',
	    'csrf_protection' => false
	));
    }

    /**
     * @return string
     */
    public function getName() {
	return '';
    }

}
