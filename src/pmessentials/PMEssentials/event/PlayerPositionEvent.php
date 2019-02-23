<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\Player;

class PlayerPositionEvent extends PlayerCommandEvent implements Cancellable{

    protected $position;

    public function __construct(Player $player, CommandSender $sender, Position $position){
        parent::__construct($player, $sender);
        $this->position = $position;
    }

    public function getPosition() : Position {
        return $this->position;
    }

    public function setPosition(Position $position) : void{
        $this->position = $position;
    }
}