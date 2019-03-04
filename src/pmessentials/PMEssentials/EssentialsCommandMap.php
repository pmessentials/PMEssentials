<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\command\BreakCommand;
use pmessentials\PMEssentials\command\BurnCommand;
use pmessentials\PMEssentials\command\ExtinguishCommand;
use pmessentials\PMEssentials\command\FeedCommand;
use pmessentials\PMEssentials\command\FlyCommand;
use pmessentials\PMEssentials\command\GameModeCommand;
use pmessentials\PMEssentials\command\GodCommand;
use pmessentials\PMEssentials\command\HealCommand;
use pmessentials\PMEssentials\command\ICommand;
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
use pmessentials\PMEssentials\listener\PowertoolListener;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandMap;
use pocketmine\command\PluginCommand;
use pocketmine\command\SimpleCommandMap;
use pocketmine\entity\Entity;
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
        try{
            $cmd = new SimpleCommand("nick", $this->plugin);
            $cmd->setExecutor(new NickCommand());
            $cmd->setDescription("change your nickname");
            $cmd->setPermission(Main::PERMISSION_PREFIX."nick.self");
            $cmd->setAliases(["name", "nickname"]);
            $cmd->setUsage("/nick [nick] [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: nick"));
            $this->error($e);
        }


        try{
            $cmd = new SimpleCommand("heal", $this->plugin);
            $cmd->setExecutor(new HealCommand());
            $cmd->setDescription("heal a player");
            $cmd->setPermission(Main::PERMISSION_PREFIX."heal.self");
            $cmd->setUsage("/heal [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: heal"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("feed", $this->plugin);
            $cmd->setExecutor(new FeedCommand());
            $cmd->setDescription("feed a player");
            $cmd->setPermission(Main::PERMISSION_PREFIX."feed.self");
            $cmd->setUsage("/feed [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: feed"));
            $this->error($e);
        }

        try{
            $pmgm = $this->plugin->getServer()->getCommandMap()->getCommand("gamemode");
            $this->plugin->getServer()->getCommandMap()->unregister($pmgm);

            $cmd = new SimpleCommand("gamemode", $this->plugin);
            $cmd->setExecutor(new GameModeCommand());
            $cmd->setDescription("change your gamemode");
            $cmd->setPermission(Main::PERMISSION_PREFIX."gamemode.self");
            $cmd->setAliases(["gm", "gms", "gmc", "gma", "gmspc", "gmv"]);
            $cmd->setUsage("/gamemode <mode> [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: gamemode"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("i", $this->plugin);
            $cmd->setExecutor(new ICommand());
            $cmd->setDescription("gives you an item");
            $cmd->setPermission(Main::PERMISSION_PREFIX."i");
            $cmd->setUsage("/i <item>:[meta] [count]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: i"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("size", $this->plugin);
            $cmd->setExecutor(new SizeCommand());
            $cmd->setDescription("resize a player");
            $cmd->setPermission(Main::PERMISSION_PREFIX."size.self");
            $cmd->setAliases(["scale"]);
            $cmd->setUsage("/size [size] [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: size"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("realname", $this->plugin);
            $cmd->setExecutor(new RealNameCommand());
            $cmd->setDescription("view someone's real name");
            $cmd->setPermission(Main::PERMISSION_PREFIX."realname");
            $cmd->setUsage("/realname <nick>");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: realname"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("usage", $this->plugin);
            $cmd->setExecutor(new UsageCommand());
            $cmd->setDescription("Check a command's usage");
            $cmd->setPermission(Main::PERMISSION_PREFIX."usage");
            $cmd->setAliases(["howtouse"]);
            $cmd->setUsage("/usage <command>");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: usage"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("powertool", $this->plugin);
            $cmd->setExecutor(new PowertoolCommand());
            $cmd->setDescription("Assign a command to an item");
            $cmd->setPermission(Main::PERMISSION_PREFIX."powertool.set");
            $cmd->setAliases(["pt"]);
            $cmd->setUsage("/powertool <command>");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: powertool"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("ping", $this->plugin);
            $cmd->setExecutor(new PingCommand());
            $cmd->setDescription("Pong!");
            $cmd->setPermission(Main::PERMISSION_PREFIX."ping");
            $cmd->setUsage("/ping");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: ping"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("fly", $this->plugin);
            $cmd->setExecutor(new FlyCommand());
            $cmd->setDescription("enable/disable flight");
            $cmd->setPermission(Main::PERMISSION_PREFIX."fly.self");
            $cmd->setUsage("/fly [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: fly"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("vanish", $this->plugin);
            $cmd->setExecutor(new VanishCommand());
            $cmd->setDescription("enable/disable vanish");
            $cmd->setPermission(Main::PERMISSION_PREFIX."vanish.self");
            $cmd->setAliases(["v", "invis"]);
            $cmd->setUsage("/vanish [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: vanish"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("speed", $this->plugin);
            $cmd->setExecutor(new SpeedCommand());
            $cmd->setDescription("change your speed");
            $cmd->setPermission(Main::PERMISSION_PREFIX."speed.self");
            $cmd->setAliases(["velocity"]);
            $cmd->setUsage("/speed <speed> [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: speed"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("xyz", $this->plugin);
            $cmd->setExecutor(new PosCommand());
            $cmd->setDescription("show your coordinates");
            $cmd->setPermission(Main::PERMISSION_PREFIX."xyz.self");
            $cmd->setAliases(["getpos", "position"]);
            $cmd->setUsage("/xyz [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: xyz"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("godmode", $this->plugin);
            $cmd->setExecutor(new GodCommand());
            $cmd->setDescription("toggle godmode");
            $cmd->setPermission(Main::PERMISSION_PREFIX."godmode.self");
            $cmd->setAliases(["god"]);
            $cmd->setUsage("/godmode [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: godmode"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("nuke", $this->plugin);
            $cmd->setExecutor(new NukeCommand());
            $cmd->setDescription("nuke someone");
            $cmd->setPermission(Main::PERMISSION_PREFIX."nuke.self");
            $cmd->setUsage("/nuke [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: nuke"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("smite", $this->plugin);
            $cmd->setExecutor(new ThorCommand());
            $cmd->setDescription("Thou hast been smitten");
            $cmd->setPermission(Main::PERMISSION_PREFIX."smite");
            $cmd->setAliases(["thor", "lightning"]);
            $cmd->setUsage("/smite");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: smite"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("tree", $this->plugin);
            $cmd->setExecutor(new TreeCommand());
            $cmd->setDescription("Spawn a tree");
            $cmd->setPermission(Main::PERMISSION_PREFIX."tree");
            $cmd->setUsage("/tree");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: smite"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("break", $this->plugin);
            $cmd->setExecutor(new BreakCommand());
            $cmd->setDescription("break the target block");
            $cmd->setPermission(Main::PERMISSION_PREFIX."break");
            $cmd->setUsage("/break");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: break"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("thru", $this->plugin);
            $cmd->setExecutor(new ThruCommand());
            $cmd->setDescription("go through a block");
            $cmd->setPermission(Main::PERMISSION_PREFIX."thru");
            $cmd->setUsage("/thru");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: break"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("tpa", $this->plugin);
            $cmd->setExecutor(new TpaCommand());
            $cmd->setDescription("send a tpa request");
            $cmd->setPermission(Main::PERMISSION_PREFIX."tpa");
            $cmd->setUsage("/tpa <player>");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: tpa"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("tpahere", $this->plugin);
            $cmd->setExecutor(new TpahereCommand());
            $cmd->setDescription("send a tpahere request");
            $cmd->setPermission(Main::PERMISSION_PREFIX."tpahere");
            $cmd->setUsage("/tpahere <player>");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: tpahere"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("tpaccept", $this->plugin);
            $cmd->setExecutor(new TpacceptCommand());
            $cmd->setDescription("accept/deny a teleport request");
            $cmd->setPermission(Main::PERMISSION_PREFIX."tpaccept");
            $cmd->setAliases(["tpdeny"]);
            $cmd->setUsage("/tpaccept");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: tpaccept/tpdeny"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("burn", $this->plugin);
            $cmd->setExecutor(new BurnCommand());
            $cmd->setDescription("set someone on fire");
            $cmd->setPermission(Main::PERMISSION_PREFIX."burn");
            $cmd->setUsage("/burn [player] [seconds]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: burn"));
            $this->error($e);
        }

        try{
            $cmd = new SimpleCommand("extinguish", $this->plugin);
            $cmd->setExecutor(new ExtinguishCommand());
            $cmd->setDescription("extinguish someone");
            $cmd->setPermission(Main::PERMISSION_PREFIX."extinguish");
            $cmd->setAliases(["ext"]);
            $cmd->setUsage("/extinguish [player]");
            $this->register($cmd);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: extinguish"));
            $this->error($e);
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