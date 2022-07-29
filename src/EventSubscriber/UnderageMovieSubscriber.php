<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Events\UnderageMovieEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class UnderageMovieSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserRepository $userRepository, private Security $security)
    {
    }

    public function onUnderageMovieEvent(UnderageMovieEvent $event): void
    {
        /** @var User[] $admins */
        $admins = $this->userRepository->findAdmins();
        foreach ($admins as $admin) {
            dump('admin : '.$admin->getUserIdentifier(), $this->security->getUser(), $event->getMovie());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UnderageMovieEvent::NAME => 'onUnderageMovieEvent',
        ];
    }
}
