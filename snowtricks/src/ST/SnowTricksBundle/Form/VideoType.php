<?php

    namespace ST\SnowTricksBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class VideoType extends AbstractType
    {
      public function buildForm(FormBuilderInterface $builder, array $options)
      {
          $builder
            ->add('path', TextType::class, array(
              'label' => 'Lien',
              'attr' => ['class' => 'form-control']
            ));
      }

      public function configureOptions(OptionsResolver $resolver)
      {
          $resolver->setDefaults(array(
              'data_class' => 'ST\SnowTricksBundle\Entity\Video'
          ));
      }
    }
