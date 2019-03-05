<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class VanishCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."vanish.other")){
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
            if(empty($match)){
                if($this->api->getVanishedPlayer($args[0]) !== null){
                    $player = $this->api->getVanishedPlayer($args[0]);
                }else{
                    $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                    return true;
                }
            }else{
                $player = $match[0];
            }
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to vanish someone else!"));
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        $ev = new PlayerVanishEvent($player, $sender, !$this->api->isVanished($player));
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if($ev->getVanish()){
            $this->api->vanish($player);
            if($player === $sender){
                $sender->sendMessage(TextFormat::colorize("&6You have vanished!"));
            }else{
                $sender->sendMessage(TextFormat::colorize("&6Enabled vanish for &c".$player->getName()."&r&6."));
                $player->sendMessage(TextFormat::colorize("&6You have vanished!"));
            }
        }else{
            $this->api->unvanish($player);
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