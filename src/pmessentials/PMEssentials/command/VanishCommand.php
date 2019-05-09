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

    public function __construct(){
        parent::__construct();
        $this->name = "vanish";
        $this->description = "enable/disable vanish";
        $this->permission = Main::PERMISSION_PREFIX."vanish.self";
        $this->aliases = ["v", "invis"];
        $this->usage = "/vanish [player]";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."vanish.other")){
            $match = $match = $this->api->matchPlayer($args[0], $sender);
            if (empty($match)) {
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c" . $args[0] . "&r&4 not found!"));
                return true;
            }
            $player = $match[0]->getPlayer();
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to vanish someone else!"));
            return true;
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