<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchClientSubscriber implements EventSubscriberInterface
{
    public function onSubmit(FormEvent $event): void
    {
        // Récupérer le formulaire et les données soumises
        $form = $event->getForm();
        $data = $event->getData();

        // Exemple de modification des données avant la validation
        if (!empty($data['telephon'])) {
            $data['telephon'] = str_replace(' ', '', $data['telephon']); // Supprimer les espaces dans le numéro de téléphone
        }

        // Définir les données modifiées
        $event->setData($data);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }
}
