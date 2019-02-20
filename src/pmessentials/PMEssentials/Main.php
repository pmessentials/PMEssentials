<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\listener\PowertoolListener;
use pmessentials\PMEssentials\listener\VanishListener;
use pmessentials\PMEssentials\module\ModuleManager;
use pmessentials\PMEssentials\module\PowertoolModule;
use pmessentials\PMEssentials\module\VanishModule;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{

    /** @var API */
    public $api;
    /** @var ModuleManager */
    public $moduleManager;

    public $listeners = [];

    public $commandMap;

    private static $instance;

    public function onLoad(){
        self::$instance = $this;
        $this->api = API::getAPI();
        $this->moduleManager = new ModuleManager($this);
    }

    public function onEnable() : void{
        if(!$this->getServer()->getPluginManager()->isCompatibleApi("4.0.0")){
            $this->getLogger()->warning(TextFormat::colorize("&cWarning: &ethis plugin is being developed for API 4.0.0! You are not running on 4.0.0 so not all features may work correctly."));
        }
	    $this->moduleManager->addModule(new PowertoolModule($this));
        $this->moduleManager->addModule(new VanishModule($this));

	    $this->commandMap = EssentialsCommandMap::getInstance();
	}

	public function onDisable() : void{
	}

	public static function getInstance() : Main{
        return self::$instance;
    }

    public function getModuleManager() : ModuleManager{
        return $this->moduleManager;
    }
}