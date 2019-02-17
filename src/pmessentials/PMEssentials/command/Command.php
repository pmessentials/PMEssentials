<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

abstract class Command implements CommandExecutor {

    protected $plugin;
    protected $api;

    public function __construct(Main $plugin, API $api){
        $this->plugin = $plugin;
        $this->api = API::getAPI();
    }

    abstract public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args) : bool;
}