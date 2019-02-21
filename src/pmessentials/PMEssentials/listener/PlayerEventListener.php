<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\User;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;

class PlayerEventListener extends ListenerBase {

    public function onJoin(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        $user = new User($player);
        $this->plugin->getUserMap()->addUser($user);
        $this->plugin->getServer()->getLogger()->debug("registered user ".$event->getPlayer()->getName());
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $this->plugin->getUserMap()->removeUser($this->plugin->getUserMap()->fromPlayer($event->getPlayer()));
        $this->plugin->getServer()->getLogger()->debug("unregistered user ".$event->getPlayer()->getName());
    }
}