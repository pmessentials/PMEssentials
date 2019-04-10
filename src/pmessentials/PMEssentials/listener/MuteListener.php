<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;

class MuteListener extends ListenerBase {

    public function onChat(PlayerChatEvent $event){
        if($this->api->isMuted($event->getPlayer())){
            $this->plugin->getLogger()->info(TextFormat::colorize("&c[MUTED] ".$event->getPlayer()->getDisplayName().": &r".$event->getMessage()));
            $event->getPlayer()->sendMessage(TextFormat::colorize("&4You can't talk while muted!"));
            $event->setCancelled();
        }
    }

}