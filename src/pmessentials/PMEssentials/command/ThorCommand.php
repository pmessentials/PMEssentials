<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerHealEvent;
use pmessentials\PMEssentials\event\ThorEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ThorCommand extends SimpleExecutor {

    protected $cooldown = [];
    protected $wait = 5*60;

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        $this->wait = $this->plugin->config->get("smite.cooldown");
        $t = microtime(true);

        if (isset($this->cooldown[$sender->getName()]) && $this->cooldown[$sender->getName()] + $this->wait > $t && !$sender->hasPermission(Main::PERMISSION_PREFIX."feed.instant")) {
            $min = (int)floor(($this->cooldown[$sender->getName()] + $this->wait - $t)/60);
            if($min == 0){
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }else{
                $sender->sendMessage(TextFormat::colorize("&4You need to wait &c" . $min . "&4 minutes and &c".date("s", (int)$this->cooldown[$sender->getName()] + $this->wait - (int)$t)."&4 seconds before you can use this command again."));
            }
            return true;
        }

        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player."));
            return true;
        }
        $ev = new ThorEvent($sender, $sender->getTargetBlock(100));
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        if (!$sender->hasPermission(Main::PERMISSION_PREFIX."smite.instant")) {
            $this->cooldown[$sender->getName()] = $t;
        }

        $this->addStrike($ev->getPosition());
        $explosion = new Explosion($ev->getPosition(), 3);
        $explosion->explodeB();
        $sender->sendMessage(TextFormat::colorize("&6Smiting!"));
        return true;
    }

    public function addStrike(Position $p){
        $level = $p->getLevel();
        $light = new AddEntityPacket();
        $light->type = 93;
        $light->entityRuntimeId = Entity::$entityCount++;
        $light->metadata = array();
        $light->position = $p->asVector3();
        $this->plugin->getServer()->broadcastPacket($level->getPlayers(),$light);
    }
}