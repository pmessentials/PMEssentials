<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use AndreasHGK\AutoCompleteAPI\AutoCompleteAPI;
use AndreasHGK\AutoCompleteAPI\CustomCommandData;
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
        $this->setDefaultParameters();
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
            new RealNameCommand(), //todo: add autocompletion
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

    private function setDefaultParameters() : void{
        $autoCompleteAPI = $this->plugin->getServer()->getPluginManager()->getPlugin("AutoCompleteAPI");
        if($autoCompleteAPI instanceof AutoCompleteAPI){
            $this->plugin->getLogger()->notice("AutoCompleteAPI detected. Enabling support.");
            $cmap = $this->plugin->getServer()->getCommandMap();
            $cmd = $cmap->getCommand("nick");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof NickCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_STRING, "Nickname", true);
                $data->normalParameter(0, 1, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("heal");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof HealCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("feed");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof FeedCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("gamemode");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof GameModeCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->arrayParameter(0, 0, "Gamemode", ["survival", "creative", "adventure", "spectator"], true);
                $data->normalParameter(0, 1, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("i");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof ICommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->magicParameter(0, 0, CustomCommandData::MAGIC_TYPE_ITEM, "Item");
                $data->normalParameter(0, 1, CustomCommandData::ARG_TYPE_INT, "Count", true);
            }

            $cmd = $cmap->getCommand("size");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof SizeCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_INT, "Size", true);
                $data->normalParameter(0, 1, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("usage");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof UsageCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_COMMAND, "Command");
            }

            $cmd = $cmap->getCommand("powertool");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof PowertoolCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_COMMAND, "Command");
                $data->normalParameter(1, 0, CustomCommandData::ARG_TYPE_MESSAGE, "Message");
            }

            $cmd = $cmap->getCommand("fly");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof FlyCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("vanish");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof VanishCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("speed");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof SpeedCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_INT, "Speed", true);
                $data->normalParameter(0, 1, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("xyz");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof PosCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("godmode");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof GodCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("nuke");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof NukeCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("tpa");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof TpaCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("tpahere");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof TpahereCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("burn");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof BurnCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("extinguish");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof ExtinguishCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("clearinventory");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof ClearinventoryCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("milk");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof MilkCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
            }

            $cmd = $cmap->getCommand("mute");
            if($cmd instanceof SimpleCommand && $cmd->getExecutor() instanceof MuteCommand){
                $data = $autoCompleteAPI->registerCommandData($cmd);
                $data->normalParameter(0, 0, CustomCommandData::ARG_TYPE_TARGET, "Player", true);
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