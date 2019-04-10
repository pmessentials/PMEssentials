<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerSizeChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SizeCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[1]) && $sender->hasPermission(Main::PERMISSION_PREFIX."size.other")){
            $match = $match = $this->api->matchPlayer($args[1], $sender);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[1]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to change someone else's size!"));
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        if(!isset($args[0])){
            $size = 1;
        }elseif(is_numeric($args[0]) && $args[0] > 0){
            $size = $args[0];
        }else{
            $sender->sendMessage(TextFormat::colorize("&4Please enter a valid size, greater than 0!"));
            return true;
        }

        $ev = new PlayerSizeChangeEvent($player, $sender, $size);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $player->setScale($ev->getSize());
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You have been resized to &c".$ev->getSize()."&6."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Resized &c".$player->getName()."&r&6 to &c".$ev->getSize()."&6."));
            $player->sendMessage(TextFormat::colorize("&6You have been resized to &c".$ev->getSize()."&6."));
        }
        return true;
    }
}