<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerVanishEvent extends PlayerCommandEvent implements Cancellable{

    protected $vanish;
    protected $canceled = false;

    public function __construct(Player $player, CommandSender $sender, bool $vanish){
        parent::__construct($player, $sender);
        $this->player = $player;
        $this->vanish = $vanish;
    }

    public function getVanish() : bool{
        return $this->vanish;
    }

    public function setVanish(bool $vanish) : void{
        $this->vanish = $vanish;
    }
}