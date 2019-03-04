<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerFeedEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class FeedCommand extends SimpleExecutor {

    protected $cooldown = [];
    protected $wait = 2*60;

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool{
        $this->wait = $this->plugin->config->get("feed.cooldown");
        $t = microtime(true);

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] + $this->wait > $t && !$sender->hasPermission(Main::PERMISSION_PREFIX."feed.instant")) {
            $min = (int)floor(($this->cooldown[$sender->getName()] + $this->wait - $t)/60);
            if($min == 0){
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can feed again."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c" . $min . "&4 minutes and &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can feed again."));
            }
            return true;
        }

        if (isset($args[0]) && $sender->hasPermission(Main::PERMISSION_PREFIX."feed.other")) {
            $match = $this->plugin->getServer()->matchPlayer($args[0]);
            if (empty($match)) {
                $sender->sendMessage(TextFormat::colorize("&4Player with name &c" . $args[0] . "&r&4 not found!"));
                return true;
            }
            $player = $match[0];
        } else {
            $player = $sender;
        }

        if (!$player instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&4Target needs to be a player."));
            return true;
        }
        $ev = new PlayerFeedEvent($player, $sender, $player->getMaxFood());
        $ev->call();
        if ($ev->isCancelled()) {
            return true;
        }
        if (!$sender->hasPermission(Main::PERMISSION_PREFIX."feed.instant")) {
            $this->cooldown[$sender->getName()] = $t;
        }

        $player->setFood($ev->getFood());
        if ($player === $sender) {
            $sender->sendMessage(TextFormat::colorize("&6You have been fed!"));
        } else {
            $sender->sendMessage(TextFormat::colorize("&6Restored &c" . $player->getName() . "&r&6's food."));
            $player->sendMessage(TextFormat::colorize("&6You have been fed!"));
        }
        return true;
    }
}