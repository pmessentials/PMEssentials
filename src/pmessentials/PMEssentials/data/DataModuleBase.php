<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\data;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\utils\TextFormat;

abstract class DataModuleBase{

    protected $plugin;
    protected $api;

    protected $data;

    public function __construct(Main $plugin, API $api){
        $this->plugin = $plugin;
        $this->api = $api;
    }

    abstract public function load() : void;

    abstract public function save() : void;

    abstract public function get($value);

    abstract public function set($value);
}