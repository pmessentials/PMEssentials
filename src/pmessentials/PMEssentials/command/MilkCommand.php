<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerBurnEvent;
use pmessentials\PMEssentials\event\PlayerMilkEvent;
use pmessentials\PMEssentials\event\PlayerSizeChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class MilkCommand extends SimpleExecutor {

    protected $cooldown = [];
    protected $wait = 2*60;

    public function __construct(){
        parent::__construct();
        $this->name = "milk";
        $this->description = "clear all effects";
        $this->permission = Main::PERMISSION_PREFIX."milk.self";
        $this->aliases = ["cure"];
        $this->usage = "/milk [player]";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $this->wait = $this->plugin->config->get("milk.cooldown");
        $t = microtime(true);

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] + $this->wait > $t && !$sender->hasPermission(Main::PERMISSION_PREFIX."milk.instant")) {
            $min = (int)floor(($this->cooldown[$sender->getName()] + $this->wait - $t)/60);
            if($min == 0){
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c" . $min . "&4 minutes and &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }
            return true;
        }

        if(isset($args[0])){
            $match = $match = $this->api->matchPlayer($args[0], $sender->hasPermission(Main::PERMISSION_PREFIX."vanish.see"));
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to feed someone else!"));
            return true;
        } else{
            $player = $sender;
        }

        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        if(!$player->hasEffects() && $player === $sender){
            $sender->sendMessage(TextFormat::colorize("&4You don't have any effects!"));
            return true;
        }elseif(!$player->hasEffects()){
            $sender->sendMessage(TextFormat::colorize("&4The target doesn't have any effects!"));
            return true;
        }

        $ev = new PlayerMilkEvent($player, $sender);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if (!$sender->hasPermission(Main::PERMISSION_PREFIX."milk.instant")) {
            $this->cooldown[$sender->getName()] = $t;
        }

        $player->removeAllEffects();
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6Cleared all effects."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Cleared all &c".$player->getName()."&6's effects."));
        }
        return true;
    }
}