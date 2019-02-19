<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerHealEvent extends PlayerCommandEvent implements Cancellable{

    protected $health;

    public function __construct(Player $player, CommandSender $sender, float $health){
        parent::__construct($player, $sender);
        $this->health = $health;
    }

    public function getHealth() : float {
        return $this->health;
    }

    public function setHealth(float $health) : void{
        $this->health = $health;
    }
}