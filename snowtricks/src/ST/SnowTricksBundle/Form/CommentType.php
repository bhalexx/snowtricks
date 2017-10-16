<?php

	namespace ST\SnowTricksBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\OptionsResolver\OptionsResolver;

	class CommentType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('content', TextareaType::class)
			;
		}

		public function configureOptions(OptionsResolver $resolver)
	    {
	        $resolver->setDefaults(array(
	            'data_class' => 'ST\SnowTricksBundle\Entity\Comment'
	        ));
	    }
	}