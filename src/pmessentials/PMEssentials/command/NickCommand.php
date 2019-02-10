<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class NickCommand extends Command {

    public function __construct(Main $plugin, API $api){
        parent::__construct($plugin, $api);
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[1]) && $sender->hasPermission("pmessentials.nick.other")){
            $match = $this->plugin->getServer()->matchPlayer($args[1]);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[1]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player"));
            return true;
        }

        if(!isset($args[0])){
            $player->setDisplayName($player->getName());
            if($player === $sender){
                $sender->sendMessage(TextFormat::colorize("&6Your nick has been cleared."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&6Cleared ".$player->getName()."&6's nick"));
                $player->sendMessage(TextFormat::colorize("&6Your nick has been cleared."));
            }
            return true;
        }

        $str = str_replace("+", " ", $args[0]);
        $str = TextFormat::colorize($str);
        if(!$sender->hasPermission("pmessentials.nick.color")){
            $str = TextFormat::clean($str);
        }
        if(!$sender->hasPermission("pmessentials.nick.custom") && strtolower($player->getName()) != strtolower(TextFormat::clean($str))){
            $sender->sendMessage(TextFormat::colorize("&4You're not allowed to set custom nicknames"));
            return true;
        }
        $player->setDisplayName($str.TextFormat::RESET);
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Your nick has been set to &c".$str."&r&6."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Set ".$player->getName()."&6's nick to &r&c".$str."&r&6."));
            $player->sendMessage(TextFormat::colorize("&6Your nick has been set to &c".$str."&r&6."));
        }
        return true;
    }
}