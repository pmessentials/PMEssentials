<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerFeedEvent extends PlayerCommandEvent implements Cancellable{

    protected $food;

    public function __construct(Player $player, CommandSender $sender, float $food){
        parent::__construct($player, $sender);
        $this->food = $food;
    }

    public function getFood() : float {
        return $this->food;
    }

    public function setFood(float $food) : void{
        $this->food = $food;
    }
}