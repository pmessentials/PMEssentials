<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\module;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\utils\TextFormat;

abstract class ModuleBase{

    protected $plugin;
    protected $api;

    protected $name;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->api = API::getAPI();
        $this->name = self::class;
        $this->onStart();
    }

    public function getName() : string{
        return $this->name;
    }

    abstract public function onStart() : void;
}