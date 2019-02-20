<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\module;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\listener\PowertoolListener;
use pmessentials\PMEssentials\Main;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\TextFormat;

class PowertoolModule extends ModuleBase {

    public $counter = [];
    public $cooldown = [];

    public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->name = self::class;
    }

    public function onStart() : void{
        $this->plugin->listeners["PowertoolListener"] = new PowertoolListener();
    }

    public function disablePowertool(Item $item) : item{
        $nbt = $item->getNamedTag();
        $nbt->removeTag("powertool");
        $item->setNamedTag($nbt);
        return $item;
    }

    public function enablePowertool(item $item, string $command) : item{
        $nbt = $item->getNamedTag();
        $nbt->setString("powertool", $command, true);
        $item->setNamedTag($nbt);

        return $item;
    }

    public function isPowertool(item $item) : bool{
        $nbt = $item->getNamedTag();
        return $nbt->hasTag("powertool", StringTag::class);
    }

    public function checkCommand(item $item) : string{
        $nbt = $item->getNamedTag();
        return $nbt->getString("powertool");
    }
}