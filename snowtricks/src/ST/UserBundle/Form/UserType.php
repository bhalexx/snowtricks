<?php

	namespace ST\UserBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;

	class UserType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('firstname', TextType::class, array(
					'label' => 'Prénom'
				))
				->add('lastname', TextType::class, array(
					'label' => 'Nom'
				))
				->add('file', FileType::class, array(
					'label' => 'Photo de profil',
					'required' => false
				))
				->add('groups', EntityType::class, array(
					'label' => 'Assigner au groupe',
					'class' => 'STUserBundle:Group',
					'choice_label' => 'label',
					'multiple' => true,
					'expanded' => true,
					'required' => true

				))
				->remove('plainPassword')
			;
		}

		public function getParent()
	    {
	        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
	    }

	    public function getBlockPrefix()
	    {
	        return 'st_user_edit';
	    }
	}