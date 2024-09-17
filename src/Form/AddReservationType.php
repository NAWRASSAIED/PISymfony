<?php

namespace App\Form;

use App\Entity\Reservations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;





class AddReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('cinclient', IntegerType::class, [ // Use NumberType
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ cinclient est obligatoire.',
                ]),
                new Assert\Length([
                    'min' => 8,
                    'max' => 8,
                    'exactMessage' => 'Le champ cinclient doit comporter exactement 8 chiffres.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[01]\d*$/',
                    'message' => 'Le champ cinclient doit commencer par 1 ou 0 et contenir des chiffres.',
                ]),
            ],
        ])
        ->add('nomclient', TextType::class, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ nomclient est obligatoire.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[A-Za-z\s]*$/',
                    'message' => 'Le champ nomclient ne doit contenir que des caractères alphabétiques et des espaces.',
                ]),
            ],
        ])
        ->add('nombrepersonnes', TextType::class, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ nombrepersonnes est obligatoire.',
                ]),
            ],
        ])
        ->add('datedebut', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false, // Allow null values
            'constraints' => [
                new Assert\NotNull([
                    'message' => 'Le champ datedebut ne peut pas être vide.',
                ]),
                new Assert\GreaterThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date de début doit être aujourd\'hui ou ultérieure.',
                ]),
            ],
        ])
        
        ->add('datefin', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'constraints' => [
                new Assert\NotNull([
                    'message' => 'Le champ datefin ne peut pas être vide.',
                ]),
                new Assert\GreaterThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date de fin doit être après la date de début.',
                ]),
            ],
        ])
        

        ->add('modePaiement', TextType::class, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ modePaiement est obligatoire.',
                ]),
            ],
        ])
        ->add('typehebergement', ChoiceType::class, [
            'choices' => [
                'Hôtel' => 'Hôtel',
                'Auberge de jeunesse' => 'Auberge de jeunesse',
                'Maison d\'hôtes (B&B)' => 'Maison d\'hôtes (B&B)',
                'Location de vacances' => 'Location de vacances',
                'Camping' => 'Camping',
                'Cabane dans les arbres' => 'Cabane dans les arbres',
                'Gîte rural' => 'Gîte rural',
                'Motels' => 'Motels',
            ],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ typehebergement est obligatoire.',
                ]),
            ],
        ])
        ->add('typeactivite', ChoiceType::class, [
            'choices' => [
                'Randonnée en montagne' => 'Randonnée en montagne',
                'Visite de musées' => 'Visite de musées',
                'Plongée sous-marine' => 'Plongée sous-marine',
                'Excursion en bateau' => 'Excursion en bateau',
                'Safari' => 'Safari',
                'Excursion en vélo' => 'Excursion en vélo',
                'Visite de monuments historiques' => 'Visite de monuments historiques',
            ],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ typeactivite est obligatoire.',
                ]),
            ],
        ])
        ->add('numtel', IntegerType::class, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ numtel est obligatoire.',
                ]),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'Le champ numtel ne doit contenir que des chiffres.',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'je réserve!',
        ]);
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
