<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class TeleportRespondEvent extends PlayerEvent implements Cancellable{

    protected $position;
    protected $accept;

    protected $canceled = false;

    public function __construct(Player $player, bool $accept){
        $this->player = $player;
        $this->accept = $accept;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function isAccepted() : bool {
        return $this->accept;
    }

}