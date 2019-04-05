<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;

class VanishListener extends ListenerBase {

    public function onJoin(PlayerJoinEvent $e) : void{
        $p = $e->getPlayer();
        foreach($this->api->getVanishedPlayers() as $vanishedPlayer){
            $p->hidePlayer($vanishedPlayer->getPlayer());
        }
    }

    public function onQuit(PlayerQuitEvent $e) : void{
        $p = $e->getPlayer();
        if($this->api->isVanished($p)){
            $this->api->unvanish($p);
        }
    }

}