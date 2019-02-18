<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class VanishCommand extends Command {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $v = $this->plugin->moduleManager->getModule("VanishModule");
        if(isset($args[0]) && $sender->hasPermission("pmessentials.vanish.other")){
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
            if(empty($match)){
                if($v->getVanishedPlayer($args[0]) !== null){
                    $player = $v->getVanishedPlayer($args[0]);
                }else{
                    $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                    return true;
                }
            }
            $player = $match[0];
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player"));
            return true;
        }

        if(!$v->isVanished($player)){
            $v->vanish($player);
            if($player === $sender){
                $sender->sendMessage(TextFormat::colorize("&6You have vanished!"));
            }else{
                $sender->sendMessage(TextFormat::colorize("&6Enabled vanish for &c".$player->getName()."&r&6."));
                $player->sendMessage(TextFormat::colorize("&6You have vanished!"));
            }
        }else{
            $v->unvanish($player);
            if($player === $sender){
                $sender->sendMessage(TextFormat::colorize("&6You have reappeared!"));
            }else{
                $sender->sendMessage(TextFormat::colorize("&6Disabled vanish for &c".$player->getName()."&r&6."));
                $player->sendMessage(TextFormat::colorize("&6You have reappeared!"));
            }
        }
        return true;
    }
}