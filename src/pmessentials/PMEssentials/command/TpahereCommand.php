<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFeedEvent;
use pmessentials\PMEssentials\event\TeleportRequestEvent;
use pmessentials\PMEssentials\Main;
use pmessentials\PMEssentials\TeleportRequest;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TpahereCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool{
        if (isset($args[0])) {
            $match = $match = $this->api->matchPlayer($args[0], $sender);
            if (empty($match)) {
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c" . $args[0] . "&r&4 not found!"));
                return true;
            }
            $player = $match[0]->getPlayer();
        } else {
            $sender->sendMessage(TextFormat::colorize("&4Please specify a target!"));
            return true;
        }
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&4Target can't be yourself!"));
            return true;
        }

        if (!$player instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        $ev = new TeleportRequestEvent($player, $sender, new TeleportRequest($player, $sender, microtime(true), true));
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }

        $this->api->sendTeleportRequest($player, $ev->getRequest());
        $sender->sendMessage(TextFormat::colorize("&6Sent a teleport request to &c".$player->getName()."&6."));

        return true;
    }
}