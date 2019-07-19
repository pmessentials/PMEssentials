<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\Main;
use pmessentials\PMEssentials\module\VanishModule;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GodmodeListener extends ListenerBase {

    public function onDamage(EntityDamageEvent $event) : void{
        $player = $event->getEntity();
        if($player instanceof Player && $this->api->isGodmode($player)){
            $event->setCancelled();
        }
    }
    public function onExhaust(PlayerExhaustEvent $event) : void{
        $player = $event->getEntity();
        if($player instanceof Player && $this->api->isGodmode($player)){
            $event->setCancelled();
        }
    }
}
