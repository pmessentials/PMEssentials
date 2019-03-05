<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;



use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\Player;

class BackListener extends ListenerBase {

    public function onTeleport(EntityTeleportEvent $event){
        $player = $event->getEntity();
        if($player instanceof Player){
            $this->api->getUserMap()->fromPlayer($player)->setLastPos($player->getPosition());
        }
    }

}