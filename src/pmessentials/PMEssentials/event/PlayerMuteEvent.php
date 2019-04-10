<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerMuteEvent extends PlayerCommandEvent implements Cancellable{

    protected $mute;

    public function __construct(Player $player, CommandSender $sender, bool $mute){
        parent::__construct($player, $sender);
        $this->mute = $mute;
    }

    public function getMute() : bool{
        return $this->mute;
    }

    public function setMute(bool $mute) : void{
        $this->mute = $mute;
    }
}