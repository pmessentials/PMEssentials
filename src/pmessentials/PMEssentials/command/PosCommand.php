<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerHealEvent;
use pmessentials\PMEssentials\event\PlayerPositionEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PosCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."xyz.other")){
            $match = $match = $this->api->matchPlayer($args[0], $sender);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to view someone else's location!"));
        }else{
            $player = $sender;
        }
        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        $ev = new PlayerPositionEvent($player, $sender, $player->getLocation());
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $pos = $ev->getPosition();
        $str = "&6 position is X: &c".$pos->getFloorX()."&6, Y: &c".$pos->getFloorY()."&6, Z: &c".$pos->getFloorZ()."&6";
        if($sender->hasPermission(Main::PERMISSION_PREFIX."xyz.world") && null !== $pos->getLevel()){
            $str = $str."&6 in level &c".$pos->getLevel()->getName()."&6";
        }
        $str = $str."&6.";
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Your".$str));
        }else{
            $sender->sendMessage(TextFormat::colorize("&c".$player->getName()."&6's".$str));
        }
        return true;
    }
}