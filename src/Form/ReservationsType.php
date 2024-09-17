<?php

namespace App\Form;

use App\Entity\Reservations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cinclient')
            ->add('nomclient')
            ->add('nombrepersonnes')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd', // Modifiez le format selon vos besoins
                'required' => false, // Permet au champ de recevoir des valeurs null
            ])
            
            ->add('datedebut', DateType::class, [
    'widget' => 'single_text',
    'format' => 'yyyy-MM-dd', // Modifiez le format selon vos besoins
    'required' => false, // Permet au champ de recevoir des valeurs null
])

            ->add('modePaiement')
            ->add('typehebergement', ChoiceType::class, [
                'choices' => [
                    'Hôtel' => 'Hôtel',
                    'Auberge de jeunesse' => 'Auberge de jeunesse',
                    'Maison d\'hôtes (B&B)' => 'Maison d\'hôtes (B&B)',
                    'Location de vacances' => 'Location de vacances',
                    'Camping' => 'Camping',
                    'Cabane dans les arbres' => 'Cabane dans les arbres',
                    'Gîte rural' => 'Gîte rural',
                    // Ajoutez autant d'options que nécessaire
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
                    // Ajoutez autant d'options que nécessaire
                ],
            ])
            ->add('numtel')
            //->add('submit', SubmitType::class);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
