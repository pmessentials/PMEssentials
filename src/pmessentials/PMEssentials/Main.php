<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\listener\PlayerEventListener;
use pmessentials\PMEssentials\listener\PowertoolListener;
use pmessentials\PMEssentials\listener\VanishListener;
use pmessentials\PMEssentials\module\ModuleManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{

    /** @var API */
    public $api;
    /** @var ModuleManager */
    public $moduleManager;

    public $listeners = [];

    public $commandMap;

    public $userMap;

    private static $instance;

    public function onLoad(){
        self::$instance = $this;
        $this->api = API::getAPI();
        $this->moduleManager = new ModuleManager($this);
        $this->userMap = new UserMap();
    }

    public function onEnable() : void{
        $this->listeners["VanishListener"] = new VanishListener();
        $this->listeners["PowertoolListener"] = new PowertoolListener();
	    $this->commandMap = EssentialsCommandMap::getInstance();

	    $this->listeners[PlayerEventListener::class] = new PlayerEventListener();
	}


	public function onDisable() : void{
	}

	public static function getInstance() : Main{
        return self::$instance;
    }

    public function getModuleManager() : ModuleManager{
        return $this->moduleManager;
    }

    public function getUserMap() : UserMap{
        return $this->userMap;
    }
}