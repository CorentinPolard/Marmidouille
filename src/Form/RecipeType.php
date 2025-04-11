<?php

namespace App\Form;

use App\Entity\Step;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Difficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la recette',
                'required' => true,
            ])
            ->add('portion', IntegerType::class, [
                'label' => 'Nombre de portions',
                'required' => true,
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée de la recette (min)',
                'required' => true,
            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
                'required' => true,
            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class,
                'label' => 'Difficulté de la recette',
                'choice_label' => 'label',
            ])
            ->add('steps', CollectionType::class, [
                'label' => 'Etape :',
                'entry_type' => 'App\Form\StepType',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('ingredientQuantities', CollectionType::class, [
                'label' => 'Ingrédients et quantités',
                'entry_type' => QuantityType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
