<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\Player;

class PlayerGodmodeEvent extends PlayerCommandEvent implements Cancellable{

    protected $godmode;

    public function __construct(Player $player, CommandSender $sender, bool $godmode){
        parent::__construct($player, $sender);
        $this->godmode = $godmode;
    }

    public function getGodmode() : bool {
        return $this->godmode;
    }

    public function setGodmode(bool $godmode) : void{
        $this->godmode = $godmode;
    }
}