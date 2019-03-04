<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerBurnEvent;
use pmessentials\PMEssentials\event\PlayerExtinguishEvent;
use pmessentials\PMEssentials\event\PlayerSizeChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ExtinguishCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0])){
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        if(!$player->isOnFire()){
            $sender->sendMessage(TextFormat::colorize("&4Target is not on fire."));
        }

        $ev = new PlayerExtinguishEvent($player, $sender);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $player->extinguish();
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You have been extinguished."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Extinguished &c".$player."&6."));
            $player->sendMessage(TextFormat::colorize("&6You have been extinguished."));
        }
        return true;
    }
}