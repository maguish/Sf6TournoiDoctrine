<?php

namespace App\Form;

//AbstractType est une classe de base implémentant l’interface FormTypeInterface
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Tournoi;

use App\Form\EvenementType;


//on crée un nouveau Type de formulaire (TournoiType)
class TournoiType extends AbstractType {

    //sans association avec la classe Evenement
    /*public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder->add('nom', TextType::class)
        ->add('description', TextType::class)
        ->add('sauver', SubmitType::class, ['label' => 'Créer Tournoi !'])
        ->getForm();
    }*/

    //Gestion des associations entre entités
    //sans association avec la classe Evenement
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
        ->add('ev', EvenementType::class)
        ->add('nom', TextType::class)
        ->add('description', TextType::class)
        ->add('sauver', SubmitType::class, ['label' => 'Créer Tournoi !'])
        ->getForm();
    }

    //Pour que chaque Type de formulaire connaisse l’entité qui lui correspond
    //on ajoute une méthode configureOptions au type TournoiType
    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}