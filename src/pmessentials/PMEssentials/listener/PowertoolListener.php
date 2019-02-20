<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PowertoolEvent;
use pmessentials\PMEssentials\Main;
use pmessentials\PMEssentials\module\PowertoolModule;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;

class PowertoolListener extends ListenerBase {

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $pt = $this->plugin->moduleManager->getModule(PowertoolModule::class);
        if ($player->hasPermission("powertools.use")) {

            if (isset($pt->cooldown[$player->getName()]) && $pt->cooldown[$player->getName()] > microtime(true)) {
                $event->setCancelled();
                return;
            }elseif(isset($pt->cooldown[$player->getName()]) && $pt->cooldown[$player->getName()] + 0.5 > microtime(true)){
                if(isset($pt->counter[$player->getName()])){
                    $pt->counter[$player->getName()]++;
                }else{
                    $pt->counter[$player->getName()] = 1;
                }
            }else{
                $pt->counter[$player->getName()] = 1;
            }

            $item = $player->getInventory()->getItemInHand();
            if ($pt->isPowertool($item)) {
                $ev = new PowertoolEvent($player, $pt->checkCommand($item));
                $ev->call();
                if($ev->isCancelled()){
                    return;
                }
                $this->plugin->getServer()->dispatchCommand($player, $ev->getCommand());
                $player->addActionBarMessage(TextFormat::colorize("&6Command executed &cx".$pt->counter[$player->getName()]));
                $pt->cooldown[$player->getName()] = microtime(true) + 0.05;
                $event->setCancelled();
            }
        }
        return;
    }

}