<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class TreeEvent extends PlayerEvent implements Cancellable{

    protected $position;
    protected $canceled = false;
    protected $bigtree;

    public function __construct(Player $player, Position $position, bool $bigtree = false){
        $this->player = $player;
        $this->position = $position;
        $this->bigtree = $bigtree;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getPosition() : Position{
        return $this->position;
    }

    public function setPosition(Position $position) : void{
        $this->position = $position;
    }

    public function isBigTree() : bool {
        return $this->bigtree;
    }

    public function setBigTree(bool $bigtree = true) : void{
        $this->bigtree = $bigtree;
    }
}