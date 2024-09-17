<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numfacture', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 10,
                        'minMessage' => 'Le numéro de facture doit avoir au moins {{ limit }} caractère.',
                        'maxMessage' => 'Le numéro de facture ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'Le numéro de facture doit être un nombre.',
                    ]),
                ],
            ])
            ->add('montantFacture', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Le montant doit être un nombre.']),
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'Le montant doit être supérieur à zéro.',
                    ]),
                ],
            ])
            ->add('datePaiement', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez remplir la date de paiement.',
                    ]),
                    new Assert\Callback([
                        'callback' => [$this, 'validateDate'],
                        'payload' => 'string_validation',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'jj/mm/aaaa',
                ],
            ])
            
            
            ->add('numres')
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    
    public function validateDate(?string $datePaiement, ExecutionContextInterface $context): void
{
    if ($datePaiement === null) {
        return; // Ne rien faire si la date est nulle
    }

    $dateFormat = 'd/m/Y';
    $parsedDate = \DateTime::createFromFormat($dateFormat, $datePaiement);

    if (!$parsedDate || $parsedDate->format($dateFormat) !== $datePaiement) {
        $context->buildViolation('La date doit être au format jj/mm/aaaa.')
            ->addViolation();
    }

    // Vérifier si la date est aujourd'hui ou après
    if ($parsedDate && $parsedDate < new \DateTime('today')) {
        $context->buildViolation('La date doit être aujourd\'hui ou après.')
            ->addViolation();
    }
}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
   
    }
    



