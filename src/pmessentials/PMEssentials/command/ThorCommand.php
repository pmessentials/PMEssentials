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

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player"));
            return true;
        }
        $ev = new ThorEvent($sender, $sender->getTargetBlock(100));
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $this->addStrike($ev->getPosition());
        $explosion = new Explosion($ev->getPosition(), 3);
        $explosion->explodeB();
        $sender->sendMessage(TextFormat::colorize("&6Smiting"));
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