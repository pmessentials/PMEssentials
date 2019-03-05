<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerClearinventoryEvent;
use pmessentials\PMEssentials\event\PlayerNickChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearinventoryCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[1]) && $sender->hasPermission(Main::PERMISSION_PREFIX."clearinventory.other")){
            $match = $this->plugin->getServer()->matchPlayer($args[1]);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[1]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to clear someone else's inventory!"));
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        $ev = new PlayerClearinventoryEvent($player, $sender);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You cleared your inventory."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Cleared ".$player->getName()."&6's inventory."));
            $player->sendMessage(TextFormat::colorize("&6Your inventory has been cleared."));
        }
        return true;
    }
}