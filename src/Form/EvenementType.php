<?php

namespace App\Form;

//AbstractType est une classe de base implémentant l’interface FormTypeInterface
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Evenement;

//on crée un nouveau Type de formulaire (EvenementType)
class EvenementType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
        ->add('nom', TextType::class)
        ->add('dateDeb', DateType::class, [
            'widget' => 'single_text', // ou 'choice' ou 'text'
        ])
        ->add('dateFin', DateType::class, [
            'widget' => 'single_text', // ou 'choice' ou 'text'
        ])
        ->getForm();
    }

    //Pour que chaque Type de formulaire connaisse l’entité qui lui correspond
    //on ajoute une méthode configureOptions au type EvenementType
    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}