<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerBurnEvent;
use pmessentials\PMEssentials\event\PlayerSizeChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BurnCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "burn";
        $this->description = "set someone on fire";
        $this->permission = Main::PERMISSION_PREFIX."burn";
        $this->usage = "/burn [player] [seconds]";
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
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        if(!isset($args[1])){
            $time = 10;
        }elseif(is_numeric($args[0]) && $args[0] >= 0){
            $time = floor(abs($args[1]));
        }else{
            $sender->sendMessage(TextFormat::colorize("&4Please enter a valid amount of seconds, greater than 0!"));
            return true;
        }

        $ev = new PlayerBurnEvent($player, $sender, $time);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $player->setOnFire($ev->getSeconds());
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Burned yourself for &c".$ev->getSeconds()."&6 seconds."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Burned &c".$player->getName()."&r&6 for &c".$ev->getSeconds()."&6 seconds."));
        }
        return true;
    }
}