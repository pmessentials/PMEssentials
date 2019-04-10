<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerHealEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class HealCommand extends SimpleExecutor {

    protected $cooldown = [];
    protected $wait = 2*60;

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $this->wait = $this->plugin->config->get("heal.cooldown");
        $t = microtime(true);

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] + $this->wait > $t && !$sender->hasPermission(Main::PERMISSION_PREFIX."heal.instant")) {
            $min = (int)floor(($this->cooldown[$sender->getName()] + $this->wait - $t)/60);
            if($min == 0){
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c" . $min . "&4 minutes and &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }
            return true;
        }

        if(isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."heal.other")){
            $match = $match = $this->api->matchPlayer($args[0], $sender);
            if(empty($match)){
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c".$args[0]."&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        }elseif(isset($args[1])){
            $sender->sendMessage(TextFormat::colorize("&4You don't have permission to heal someone else!"));
            return true;
        }else{
            $player = $sender;
        }
        if(!$player instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }

        $ev = new PlayerHealEvent($player, $sender, $player->getMaxHealth());
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }

        if (!$sender->hasPermission(Main::PERMISSION_PREFIX."heal.instant")) {
            $this->cooldown[$sender->getName()] = $t;
        }
        $player->setHealth($ev->getHealth());
        if($player === $sender){
            $sender->sendMessage(TextFormat::colorize("&6You have been healed!"));
        }else{
            $sender->sendMessage(TextFormat::colorize("&6Restored &c".$player->getName()."&r&6's health."));
            $player->sendMessage(TextFormat::colorize("&6You have been healed!"));
        }
        return true;
    }
}