<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class RealNameCommand extends Command {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0])){
            $match = $this->api->matchNicknames($args[0]);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
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
        $sender->sendMessage(TextFormat::colorize("&c".$player->getDisplayName()."&r&6's real name is &c".$player->getName()."&r&6."));
        return true;
    }
}