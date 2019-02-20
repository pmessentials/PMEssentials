<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\Command;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PowertoolEvent extends PlayerEvent implements Cancellable{

    protected $command;
    protected $canceled = false;

    public function __construct(Player $player, string $command){
        $this->player = $player;
        $this->command = $command;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getCommand() : string {
        return $this->command;
    }

    public function setCommand(string $command) : void{
        $this->command = $command;
    }
}