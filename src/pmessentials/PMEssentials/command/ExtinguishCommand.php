<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerBurnEvent;
use pmessentials\PMEssentials\event\PlayerExtinguishEvent;
use pmessentials\PMEssentials\event\PlayerSizeChangeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ExtinguishCommand extends SimpleExecutor {

    protected $cooldown = [];
    protected $wait = 3*60;

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $this->wait = $this->plugin->config->get("extinguish.cooldown");
        $t = microtime(true);

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] + $this->wait > $t && !$sender->hasPermission(Main::PERMISSION_PREFIX."extinguish.instant")) {
            $min = (int)floor(($this->cooldown[$sender->getName()] + $this->wait - $t)/60);
            if($min == 0){
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c" . $min . "&4 minutes and &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }
            return true;
        }

        if(isset($args[0])){
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
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        if(!$player->isOnFire()){
            $sender->sendMessage(TextFormat::colorize("&4Target is not on fire."));
        }

        $ev = new PlayerExtinguishEvent($player, $sender);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if (!$sender->hasPermission(Main::PERMISSION_PREFIX."extinguish.instant")) {
            $this->cooldown[$sender->getName()] = $t;
        }

        $player->extinguish();
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You have been extinguished."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Extinguished &c".$player."&6."));
            $player->sendMessage(TextFormat::colorize("&6You have been extinguished."));
        }
        return true;
    }
}