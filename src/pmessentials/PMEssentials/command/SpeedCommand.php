<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerSpeedChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Attribute;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SpeedCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "speed";
        $this->description = "change your speed";
        $this->permission = Main::PERMISSION_PREFIX."speed.self";
        $this->aliases = ["velocity"];
        $this->usage = "/speed <speed> [player]";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(isset($args[1]) && $sender->hasPermission(Main::PERMISSION_PREFIX."speed.other")){
            $match = $match = $this->api->matchPlayer($args[1], $sender);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[1]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to change someone else's speed!"));
        }else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        if(!isset($args[0])){
            $size = 1;
        }elseif(is_numeric($args[0])){
            $size = abs($args[0]);
        }else{
            $sender->sendMessage(TextFormat::colorize("&4Please enter a valid speed!"));
            return true;
        }

        $ev = new PlayerSpeedChangeEvent($player, $sender, $size);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->setValue($ev->getSpeed()/10);
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Your speed has been changed to &c".$ev->getSpeed()."&6."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Changed &c".$player->getName()."&r&6's speed to &c".$ev->getSpeed()."&6."));
            $player->sendMessage(TextFormat::colorize("&6Your speed has been changed to &c".$ev->getSpeed()."&6."));
        }
        return true;
    }
}