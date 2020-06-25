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
                    'Général' => 'general',
                    'Voyages' => 'travels',
                    'Techonologies' => 'technologies',
                    'Sports' => 'sports',
                    'Loisirs' => 'leisure',
                    'Monde professionnel' => 'work',
                    'Economie' => 'economy',
                    'Groupomania' => 'groupomania',
                    'Science' => 'science',
                    'Actualité' => 'news',
                    'Divers' => 'others'
                ]])
            ->add('title',TextType::class)
            ->add('message',TextareaType::class)
            ->add('type',ChoiceType::class,
                array('choices' => array(
                        'Texte' => 'text',
                        'Musique' => 'music',
                        'Vidéo' => 'video',
                        'Image' => 'image'),
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
