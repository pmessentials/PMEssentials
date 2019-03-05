<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class PlayerBackEvent extends PlayerEvent implements Cancellable{

    protected $position;
    protected $canceled = false;

    public function __construct(Player $player, Position $position){
        $this->player = $player;
        $this->position = $position;
    }

    public function getPosition() : Position{
        return $this->position;
    }

    public function setPosition(Position $position) : void{
        $this->position = $position;
    }
}