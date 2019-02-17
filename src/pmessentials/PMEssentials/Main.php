<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\command\FeedCommand;
use pmessentials\PMEssentials\command\FlyCommand;
use pmessentials\PMEssentials\command\GameModeCommand;
use pmessentials\PMEssentials\command\HealCommand;
use pmessentials\PMEssentials\command\ICommand;
use pmessentials\PMEssentials\command\NickCommand;
use pmessentials\PMEssentials\command\PingCommand;
use pmessentials\PMEssentials\command\PowertoolCommand;
use pmessentials\PMEssentials\command\RealNameCommand;
use pmessentials\PMEssentials\command\SizeCommand;
use pmessentials\PMEssentials\command\UsageCommand;
use pmessentials\PMEssentials\listener\PowertoolListener;
use pmessentials\PMEssentials\module\ModuleManager;
use pmessentials\PMEssentials\module\PowertoolModule;
use pocketmine\command\PluginCommand;
use pocketmine\GameMode;
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
    }

    public function onEnable() : void{
	    $this->api = API::getAPI();
	    $this->moduleManager = new ModuleManager($this);
	    $this->moduleManager->addModule(new PowertoolModule($this));

	    $this->listeners["PowertoolListener"] = new PowertoolListener($this);

	    $this->commandMap = EssentialsCommandMap::getInstance();
	}

	public function onDisable() : void{
	}

	public static function getInstance() : Main{
        return self::$instance;
    }
}