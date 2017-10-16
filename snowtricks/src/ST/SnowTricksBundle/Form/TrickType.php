<?php

	namespace ST\SnowTricksBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\CollectionType;
    use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use ST\SnowTricksBundle\Form\DataTransformer\PicturesTransformer;
    use ST\SnowTricksBundle\Form\PictureType;
    use ST\SnowTricksBundle\Form\VideoType;

	class TrickType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			//Pictures are not required only if trick already exists in database
			$picturesRequired = $options['data']->getId() === null;
			
			$builder
				->add('name', TextType::class)
				->add('description', TextareaType::class)
				->add('family', EntityType::class, array(
					'class' => 'STSnowTricksBundle:Family',
					'choice_label' => 'name',
					'multiple' => false
				))
				->add(
	                $builder
	                  ->create('pictures', FileType::class, array(
	                    'multiple' => true,
	                    'required' => $picturesRequired
	                  ))
	                  ->addModelTransformer(new PicturesTransformer())
	            )
	            ->add('videos', CollectionType::class, array(
	                'entry_type' => VideoType::class,
	                'entry_options' => [
	                	'label' => false
	                ],
	                'allow_add' => true,
	                'allow_delete' => true,
	                'delete_empty' => true,
	                'required' => false,
	                'by_reference' => false
	            ))
	        ;
		}

		public function configureOptions(OptionsResolver $resolver)
	    {
	        $resolver->setDefaults(array(
	            'data_class' => 'ST\SnowTricksBundle\Entity\Trick'
	        ));
	    }
	}