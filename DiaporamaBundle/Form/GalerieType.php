<?php

namespace DiaporamaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ReferencementBundle\Form\ReferencementType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GalerieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('slug', TextType::class)
            ->add('fileimage', FileType::class)
            ->add('categorie', EntityType::class, array(
                    'class' => 'DiaporamaBundle:Categorie',
                    'choice_label' => 'nom',
                    'placeholder' => 'Choisir une catégorie'
                )
            )
            ->add('resume', TextareaType::class)
            ->add('contenu', TextareaType::class)
            ->add('referencement', ReferencementType::class)
            ->add('Enregistrer', SubmitType::class, array(
                    'attr' => array('class' => 'form-submit turquoise medium')
                )
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DiaporamaBundle\Entity\Galerie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'diaporamabundle_galerie';
    }


}
