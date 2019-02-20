<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\listener;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

abstract class ListenerBase implements Listener {

    protected $plugin;
    protected $api;

    public function __construct(){
        $this->api = API::getAPI();
        $this->plugin = $this->api->getPlugin();
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $this->plugin);
    }

}