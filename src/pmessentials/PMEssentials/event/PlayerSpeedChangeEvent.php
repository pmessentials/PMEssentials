<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerSpeedChangeEvent extends PlayerCommandEvent implements Cancellable{

    protected $speed;
    protected $canceled = false;

    public function __construct(Player $player, CommandSender $sender, float $speed){
        parent::__construct($player, $sender);
        $this->speed = $speed;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getSpeed() : float {
        return $this->speed;
    }

    public function setSpeed(float $speed) : void{
        $this->speed = $speed;
    }
}