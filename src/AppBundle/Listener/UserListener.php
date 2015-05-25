<?php

namespace AppBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\User;

class UserListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $from = $entity->getBirthday();
            $to = new \DateTime('today');
            $entity->setAgeStatus($from->diff($to)->y >= 18);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $this->sendMail($entity->getEmail());
        }
    }

    private function sendMail($to)
    {
        $subject = "Registration successful";
        $message = 'Congratulations! Your registration is successful!';

        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: Webapp <web@app.com>\r\n";

        mail($to, $subject, $message, $headers);
    }
}