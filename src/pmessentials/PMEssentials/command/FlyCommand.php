<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFlyEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class FlyCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."fly.other")){
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

        if($player->getGamemode() == 1){
            $sender->sendMessage(TextFormat::colorize("&4Can't toggle flight in creative mode!"));
            return true;
        }
        if($player->getGamemode() == 3){
            $sender->sendMessage(TextFormat::colorize("&4Can't toggle flight in spectator mode!"));
            return true;
        }

        $ev = new PlayerFlyEvent($player, $sender, !$player->getAllowFlight());
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if($ev->getFlight()){
            $str = "on";
            $player->setAllowFlight(true);
        }else{
            $str = "off";
            $player->setAllowFlight(false);
            $player->setFlying(false);
        }
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Turned &c".$str."&6 flight mode."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Turned &c".$str."&6 flight mode for &c".$player->getName()."&6."));
            $player->sendMessage(TextFormat::colorize("&6Your flight has been turned &c".$str."&6."));
        }
        return true;
    }
}