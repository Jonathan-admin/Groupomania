<?php

namespace App\Form;

use App\Entity\Content;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('topic',ChoiceType::class, [
                'choices'  => [
                    'Général' => 'Général',
                    'Voyages' => 'Voyages',
                    'Techonologies' => 'Technologies',
                    'Sports' => 'Sports',
                    'Loisirs' => 'Loisirs',
                    'Monde professionnel' => 'Le monde professionnel',
                    'Economie' => 'Economie',
                    'Groupomania' => 'Groupomania',
                    'Science' => 'Science',
                    'Actualité' => 'Actualité',
                    'Divers' => 'Autres'
                ]])
            ->add('title',TextType::class, [
                'attr' => ['placeholder' => 'Résumez en quelque mots ...'],
            ])
            ->add('message',TextareaType::class, [
                'attr' => ['placeholder' => 'Indiquez votre message ici ...'],
            ])
            ->add('type',ChoiceType::class,
                array('choices' => array(
                        'Texte' => 'Texte',
                        'Musique' => 'Musique',
                        'Vidéo' => 'Vidéo',
                        'Image' => 'Image'),
                'multiple'=>false,'expanded'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
