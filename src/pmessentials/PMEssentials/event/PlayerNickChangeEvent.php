<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerNickChangeEvent extends PlayerCommandEvent implements Cancellable{

    protected $nick;
    protected $checkedNick;

    public function __construct(Player $player, CommandSender $sender, string $nick, string $checkedNick){
        parent::__construct($player, $sender);
        $this->nick = $nick;
        $this->checkedNick = $checkedNick;
    }

    public function getNick() : string {
        return $this->nick; //this is the exact text the player has put in
    }

    public function getCheckedNick() : string {
        return $this->checkedNick; //this is the result: it is decolorized if the player doesn't have perms to set colored nicknames
    }

    public function setCheckedNick(string $checkedNick) : void{
        $this->checkedNick = $checkedNick;
    }
}