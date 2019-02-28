<?php

namespace pmessentials\PMEssentials\event;

use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class ThruEvent extends PlayerEvent implements Cancellable{

    protected $block;
    protected $canceled = false;

    public function __construct(Player $player, Block $block){
        $this->player = $player;
        $this->block = $block;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getBlock() : Block{
        return $this->block;
    }

    public function setPosition(Block $block) : void{
        $this->block = $block;
    }
}