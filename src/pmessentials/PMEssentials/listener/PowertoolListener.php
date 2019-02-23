<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PowertoolEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;

class PowertoolListener extends ListenerBase {

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $user = $this->plugin->getUserMap()->fromPlayer($player);
        $item = $player->getInventory()->getItemInHand();
        if ($player->hasPermission("powertools.use") && $this->api->isPowertool($item)) {

            if (isset($user->data["powertools"]["cooldown"][$player->getName()]) && $user->data["powertools"]["cooldown"][$player->getName()] > microtime(true)) {
                $event->setCancelled();
                return;
            }elseif(isset($user->data["powertools"]["cooldown"][$player->getName()]) && $user->data["powertools"]["cooldown"][$player->getName()] + 0.5 > microtime(true)){
                if(isset($user->data["powertools"]["counter"][$player->getName()])){
                    $user->data["powertools"]["counter"][$player->getName()]++;
                }else{
                    $user->data["powertools"]["counter"][$player->getName()] = 1;
                }
            }else{
                $user->data["powertools"]["counter"][$player->getName()] = 1;
            }

            if ($this->api->isPowertool($item)) {
                $ev = new PowertoolEvent($player, $this->api->checkCommand($item));
                $ev->call();
                if($ev->isCancelled()){
                    return;
                }
                $this->plugin->getServer()->dispatchCommand($player, $ev->getCommand());
                $player->addActionBarMessage(TextFormat::colorize("&6Command executed &cx".$user->data["powertools"]["counter"][$player->getName()]));
                $user->data["powertools"]["cooldown"][$player->getName()] = microtime(true) + 0.05;
                $event->setCancelled();
            }
        }
        return;
    }

}