<?php

	namespace ST\SnowTricksBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Ivory\CKEditorBundle\Form\Type\CKEditorType;

	class FamilyType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('name', TextType::class, array(
					'label' => 'Nom'
				))
				->add('description', CKEditorType::class, array(
					'label' => 'Description',
    				'config_name' => 'my_config',
				))
			;
		}

		public function configureOptions(OptionsResolver $resolver)
	    {
	        $resolver->setDefaults(array(
	            'data_class' => 'ST\SnowTricksBundle\Entity\Family'
	        ));
	    }
	}