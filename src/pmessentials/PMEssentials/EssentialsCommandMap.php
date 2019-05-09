<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\command\BackCommand;
use pmessentials\PMEssentials\command\BreakCommand;
use pmessentials\PMEssentials\command\BroadcastCommand;
use pmessentials\PMEssentials\command\BurnCommand;
use pmessentials\PMEssentials\command\ClearinventoryCommand;
use pmessentials\PMEssentials\command\ExtinguishCommand;
use pmessentials\PMEssentials\command\FeedCommand;
use pmessentials\PMEssentials\command\FlyCommand;
use pmessentials\PMEssentials\command\GameModeCommand;
use pmessentials\PMEssentials\command\GodCommand;
use pmessentials\PMEssentials\command\HealCommand;
use pmessentials\PMEssentials\command\ICommand;
use pmessentials\PMEssentials\command\MilkCommand;
use pmessentials\PMEssentials\command\MuteCommand;
use pmessentials\PMEssentials\command\NickCommand;
use pmessentials\PMEssentials\command\NukeCommand;
use pmessentials\PMEssentials\command\PingCommand;
use pmessentials\PMEssentials\command\PosCommand;
use pmessentials\PMEssentials\command\PowertoolCommand;
use pmessentials\PMEssentials\command\RealNameCommand;
use pmessentials\PMEssentials\command\SimpleCommand;
use pmessentials\PMEssentials\command\SizeCommand;
use pmessentials\PMEssentials\command\SpeedCommand;
use pmessentials\PMEssentials\command\ThorCommand;
use pmessentials\PMEssentials\command\ThruCommand;
use pmessentials\PMEssentials\command\TpacceptCommand;
use pmessentials\PMEssentials\command\TpaCommand;
use pmessentials\PMEssentials\command\TpahereCommand;
use pmessentials\PMEssentials\command\TreeCommand;
use pmessentials\PMEssentials\command\UsageCommand;
use pmessentials\PMEssentials\command\VanishCommand;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class EssentialsCommandMap {

    private $plugin;
    private static $instance;
    private static $api;

    private $commands = [];

    public static function getInstance() : EssentialsCommandMap{
        self::$api = API::getAPI();
        if (self::$instance == null)
        {
            self::$instance = new EssentialsCommandMap(self::$api->getPlugin());
        }

        return self::$instance;
    }

    private function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->setDefaultCommands();
    }

    private function setDefaultCommands() : void{
        $pmgm = $this->plugin->getServer()->getCommandMap()->getCommand("gamemode");
        if(isset($pmgm)){
            $this->plugin->getServer()->getCommandMap()->unregister($pmgm);
        }

        $commands = [
            new NickCommand(),
            new HealCommand(),
            new FeedCommand(),
            new GameModeCommand(),
            new ICommand(),
            new SizeCommand(),
            new RealNameCommand(),
            new UsageCommand(),
            new PowertoolCommand(),
            new PingCommand(),
            new FlyCommand(),
            new VanishCommand(),
            new SpeedCommand(),
            new PosCommand(),
            new GodCommand(),
            new NukeCommand(),
            new ThorCommand(),
            new TreeCommand(),
            new BreakCommand(),
            new ThruCommand(),
            new TpaCommand(),
            new TpahereCommand(),
            new TpacceptCommand(),
            new BurnCommand(),
            new ExtinguishCommand(),
            new BackCommand(),
            new ClearinventoryCommand(),
            new BroadcastCommand(),
            new MilkCommand(),
            new MuteCommand()
        ];
        foreach ($commands as $command){
            try{
                $cmd = new SimpleCommand($command->name, $this->plugin);
                $cmd->setExecutor($command);
                $cmd->setDescription($command->description);
                $cmd->setPermission($command->permission);
                $cmd->setAliases($command->aliases);
                $cmd->setUsage($command->usage);
                $this->register($cmd);
            }catch (\Throwable $e){
                $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: ".$command->name));
                $this->error($e);
            }
        }
    }

    public function register(Command $command) : void{
        $this->commands[$command->getName()] = $command;
        $this->plugin->getServer()->getCommandMap()->register("pmessentials", $command, $command->getName());
    }

    public function unregister(Command $command) : bool{
        foreach($this->commands as $lbl => $cmd){
            if($cmd === $command){
                unset($this->commands[$lbl]);
            }
        }

        $this->plugin->getServer()->getCommandMap()->unregister($command);

        return true;
    }

    public function getCommand(string $name) : Command{
        return $this->commands[$name] ?? null;
    }

    public function getCommands() : array{
        return $this->commands;
    }

    protected function error(\Throwable $e) : void{
        $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("&cError ".$e->getCode().": ".$e->getMessage()." on line ".$e->getLine()." in file ".$e->getFile()));
    }
}