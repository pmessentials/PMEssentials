<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;



use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class BackListener extends ListenerBase {

    public function onTeleport(EntityTeleportEvent $event){
        $player = $event->getEntity();
        if($player instanceof Player){
            if($this->api->getUserMap()->fromPlayer($player) !== null){
                $this->api->getUserMap()->fromPlayer($player)->setLastPos($player->getPosition());
            }
        }
    }


}