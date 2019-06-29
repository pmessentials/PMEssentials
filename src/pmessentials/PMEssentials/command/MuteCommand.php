<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFlyEvent;
use pmessentials\PMEssentials\event\PlayerMuteEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class MuteCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "mute";
        $this->description = "mute/unmute a player";
        $this->permission = Main::PERMISSION_PREFIX."mute";
        $this->aliases = ["unmute"];
        $this->usage = "/mute <player>";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0])){
            $match = $this->api->matchPlayer($args[0], $sender->hasPermission(Main::PERMISSION_PREFIX."vanish.see"));
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }else{
            if($label === "mute"){
                $sender->sendMessage(TextFormat::colorize("&4Please enter a player to mute!"));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4Please enter a player to unmute!"));
            }
            return true;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }
        if($label === "mute"){
            $bool = true;
        }else{
            $bool = false;
        }

        $ev = new PlayerMuteEvent($player, $sender, $bool);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if($ev->getMute()){
            if($this->api->isMuted($player)){
                $sender->sendMessage(TextFormat::colorize("&4Target is already muted."));
            }else{
                $this->api->mute($player);
                $sender->sendMessage(TextFormat::colorize("&6Muted &c".$player->getName()."&6."));
                $player->sendMessage(TextFormat::colorize("&6You have been muted!"));
            }
        }else{
            if(!$this->api->isMuted($player)){
                $sender->sendMessage(TextFormat::colorize("&4Target is not muted."));
            }else{
                $this->api->unmute($player);
                $sender->sendMessage(TextFormat::colorize("&6Unmuted &c".$player->getName()."&6."));
                $player->sendMessage(TextFormat::colorize("&6You have been unmuted!"));
            }
        }
        return true;
    }
}