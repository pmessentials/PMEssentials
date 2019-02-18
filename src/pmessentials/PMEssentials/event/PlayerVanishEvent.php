<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerVanishEvent extends PlayerEvent implements Cancellable{

    protected $bool;
    protected $canceled = false;

    public function __construct(Player $player, bool $bool){
        $this->player = $player;
        $this->bool = $bool;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getBool() : bool{
        return $this->bool;
    }

    public function setBool(bool $bool) : void{
        $this->bool = $bool;
    }
}