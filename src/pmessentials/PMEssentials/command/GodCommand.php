<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFlyEvent;
use pmessentials\PMEssentials\event\PlayerGodmodeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GodCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission("pmessentials.god.other")){
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
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player"));
            return true;
        }

        $ev = new PlayerGodmodeEvent($player, $sender, !$this->api->isGodmode($player));
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if($ev->getGodmode()){
            $str = "on";
        }else{
            $str = "off";
        }
        $this->api->setGodmode($player, $ev->getGodmode());
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Turned &c".$str."&6 godmode."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Turned &c".$str."&6 godmode for &c".$player->getName()."&6."));
            $player->sendMessage(TextFormat::colorize("&6Your godmode has been turned &c".$str."&6."));
        }
        return true;
    }
}