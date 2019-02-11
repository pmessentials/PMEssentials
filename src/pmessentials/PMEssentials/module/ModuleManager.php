<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\module;

use http\Exception\UnexpectedValueException;
use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\utils\TextFormat;

class ModuleManager{

    protected $plugin;
    protected $api;

    protected $modules = [];

    protected static $instance;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->api = API::getAPI();
        self::$instance = $this;
    }

    public static function getInstance() : ModuleManager{
        return self::$instance;
    }

    public function addModule(ModuleBase $module, bool $overwrite = false) : void{
        if(!$this->hasModule($module->getName()) || $overwrite){
            $this->modules[$module->getName()] =  $module;
        }else{
            throw new \UnexpectedValueException("Module already exists");
        }
    }

    public function delModule(string $name) : void{
        if($this->hasModule($name)){
            unset($this->modules[$name]);
        }else{
            throw new \UnexpectedValueException("Module not found");
        }
    }

    public function getModule(string $name) : ModuleBase{
        if($this->hasModule($name)){
            return $this->modules[$name];
        }else{
            throw new \UnexpectedValueException("Module not found");
        }
    }

    public function hasModule(string $name) : bool {
        if(isset($this->modules[$name])){
            return true;
        }else{
            return false;
        }
    }

}