<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class SimpleCommand extends PluginCommand {

    /** @var Plugin */
    public $owningPlugin;

    /** @var CommandExecutor */
    public $executor;

    public function __construct(string $name, Plugin $owner){
        parent::__construct($name, $owner);
        $this->owningPlugin = $owner;
        $this->executor = $owner;
        $this->usageMessage = "";
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->owningPlugin->isEnabled()){
            return false;
        }

        if(!$this->testPermission($sender)){
            return false;
        }

        $success = false;
        try{
            $success = $this->executor->onCommand($sender, $this, $commandLabel, $args);
        }catch (\Throwable $e){
            $sender->sendMessage(TextFormat::colorize("&cAn internal error has occured!"));
            $this->owningPlugin->getServer()->getLogger()->error(TextFormat::colorize("&cError ".$e->getCode().": ".$e->getMessage()." on line ".$e->getLine()." in file ".$e->getFile()));
            return false;
        }

        if(!$success and $this->usageMessage !== ""){
            throw new InvalidCommandSyntaxException();
        }

        return $success;
    }

    public function getExecutor() : CommandExecutor{
        return $this->executor;
    }

    /**
     * @param CommandExecutor $executor
     */
    public function setExecutor(CommandExecutor $executor){
        $this->executor = $executor;
    }

    /**
     * @return Plugin
     */
    public function getPlugin() : Plugin{
        return $this->owningPlugin;
    }

}