<?php

namespace App\Form;

use App\Entity\Inscription;
use App\Entity\Provincia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\ProvinciaRepository;

class InscriptionType extends AbstractType
{
    private $provinciaRepository;

    public function __construct(ProvinciaRepository $provinciaRepository)
    {
        $this->provinciaRepository = $provinciaRepository;
    }

    public function createChoices(): array
    {
        $provincias = new Provincia();
        $provincias = $this->provinciaRepository->findAllSorted();

        foreach ($provincias as $provincia) {
            $choices[$provincia->getNombre()] = $provincia->getNombre();
        }
        return $choices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CIF')
            ->add('nombre')
            ->add('email')
            ->add('telefono')
            ->add('provincia', ChoiceType::class, [
                'choices' => $this->createChoices()
            ])
            ->add('condicioneslegales', CheckboxType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('estado', CheckboxType::class, [
                'label' => 'Activo',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
