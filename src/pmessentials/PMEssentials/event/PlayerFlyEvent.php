<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerFlyEvent extends PlayerCommandEvent implements Cancellable{

    protected $flight;
    protected $canceled = false;

    public function __construct(Player $player, CommandSender $sender, bool $flight){
        parent::__construct($player, $sender);
        $this->flight = $flight;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getFlight() : bool{
        return $this->flight;
    }

    public function setFlight(bool $flight) : void{
        $this->flight = $flight;
    }
}