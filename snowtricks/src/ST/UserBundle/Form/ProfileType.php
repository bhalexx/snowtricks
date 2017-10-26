<?php

	namespace ST\UserBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\OptionsResolver\OptionsResolver;

	class ProfileType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('firstname', TextType::class)
				->add('lastname', TextType::class)
				->add('file', FileType::class, array(
					'required' => false
				))
			;
		}

		public function getParent()
	    {
	        return 'FOS\UserBundle\Form\Type\ProfileFormType';
	    }

	    public function getBlockPrefix()
	    {
	        return 'st_user_profile';
	    }
	}