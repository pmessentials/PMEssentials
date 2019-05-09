<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

abstract class SimpleExecutor implements CommandExecutor {

    protected $plugin;
    protected $api;

    public $name;
    public $description;
    public $permission;
    public $aliases = [];
    public $usage;

    public function __construct(){
        $this->api = API::getAPI();
        $this->plugin = $this->api->getPlugin();
    }

    abstract public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args) : bool;
}