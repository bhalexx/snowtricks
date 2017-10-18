<?php

	namespace ST\SnowTricksBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\OptionsResolver\OptionsResolver;

	class FamilyType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('name', TextType::class, array(
					'label' => 'Nom'
				))
				->add('description', TextareaType::class, array(
					'label' => 'Description',
					'attr' => array('class' => 'ckeditor')
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