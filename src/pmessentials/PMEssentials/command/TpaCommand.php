<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFeedEvent;
use pmessentials\PMEssentials\Main;
use pmessentials\PMEssentials\TeleportRequest;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TpaCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool{
        switch ($label) {
            case "tpaccept":
                if($sender->hasPermission(Main::PERMISSION_PREFIX."tpa.respond")) {
                    $user = $this->api->getUserMap()->fromPlayer($sender);
                    if ($user->acceptRequest()) {
                        $sender->sendMessage(TextFormat::colorize("&6Teleporting..."));
                    } else {
                        $sender->sendMessage(TextFormat::colorize("&4You have no pending requests!"));
                    }
                }else{
                    $sender->sendMessage(TextFormat::colorize("&4You don't have permission to run this command"));
                    return true;
                }
                return true;
                break;
            case "tpdeny":
                if($sender->hasPermission(Main::PERMISSION_PREFIX."tpa.respond")) {
                    $user = $this->api->getUserMap()->fromPlayer($sender);
                    if ($user->denyRequest()) {
                        $sender->sendMessage(TextFormat::colorize("&6Teleport request denied."));
                    } else {
                        $sender->sendMessage(TextFormat::colorize("&4You have no pending requests!"));
                    }
                }else{
                    $sender->sendMessage(TextFormat::colorize("&4You don't have permission to run this command"));
                    return true;
                }
                return true;
                break;
            default:
                break;
        }

        if($sender->hasPermission(Main::PERMISSION_PREFIX."tpa.tpahere") && $label == "tpahere"){
            $here = true;
        }elseif($label == "tpahere"){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to run this command"));
            return true;
        }elseif($sender->hasPermission(Main::PERMISSION_PREFIX."tpa.tpa") && $label == "tpa"){
            $here = false;
        }elseif($label == "tpa"){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to run this command"));
            return true;
        }

        if (isset($args[0])) {
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
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
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player"));
            return true;
        }

        $this->api->sendTeleportRequest($player, new TeleportRequest($player, $sender, microtime(true), $here));
        $sender->sendMessage(TextFormat::colorize("&6Sent a teleport request to &c".$player->getName()."&6."));

        return true;
    }
}