<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\command\BreakCommand;
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
use pmessentials\PMEssentials\command\TpaCommand;
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
            $nick = new SimpleCommand("nick", $this->plugin);
            $nick->setExecutor(new NickCommand());
            $nick->setDescription("change your nickname");
            $nick->setPermission(Main::PERMISSION_PREFIX."nick.self");
            $nick->setAliases(["name", "nickname"]);
            $nick->setUsage("/nick [nick] [player]");
            $this->register($nick);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: nick"));
            $this->error($e);
        }


        try{
            $heal = new SimpleCommand("heal", $this->plugin);
            $heal->setExecutor(new HealCommand());
            $heal->setDescription("heal a player");
            $heal->setPermission(Main::PERMISSION_PREFIX."heal.self");
            $heal->setUsage("/heal [player]");
            $this->register($heal);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: heal"));
            $this->error($e);
        }

        try{
            $feed = new SimpleCommand("feed", $this->plugin);
            $feed->setExecutor(new FeedCommand());
            $feed->setDescription("feed a player");
            $feed->setPermission(Main::PERMISSION_PREFIX."feed.self");
            $feed->setUsage("/feed [player]");
            $this->register($feed);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: feed"));
            $this->error($e);
        }

        try{
            $pmgm = $this->plugin->getServer()->getCommandMap()->getCommand("gamemode");
            $this->plugin->getServer()->getCommandMap()->unregister($pmgm);

            $gm = new SimpleCommand("gamemode", $this->plugin);
            $gm->setExecutor(new GameModeCommand());
            $gm->setDescription("change your gamemode");
            $gm->setPermission(Main::PERMISSION_PREFIX."gamemode.self");
            $gm->setAliases(["gm", "gms", "gmc", "gma", "gmspc", "gmv"]);
            $gm->setUsage("/gamemode <mode> [player]");
            $this->register($gm);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: gamemode"));
            $this->error($e);
        }

        try{
            $i = new SimpleCommand("i", $this->plugin);
            $i->setExecutor(new ICommand());
            $i->setDescription("gives you an item");
            $i->setPermission(Main::PERMISSION_PREFIX."i");
            $i->setUsage("/i <item>:[meta] [count]");
            $this->register($i);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: i"));
            $this->error($e);
        }

        try{
            $size = new SimpleCommand("size", $this->plugin);
            $size->setExecutor(new SizeCommand());
            $size->setDescription("resize a player");
            $size->setPermission(Main::PERMISSION_PREFIX."size.self");
            $size->setAliases(["scale"]);
            $size->setUsage("/size [size] [player]");
            $this->register($size);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: size"));
            $this->error($e);
        }

        try{
            $realname = new SimpleCommand("realname", $this->plugin);
            $realname->setExecutor(new RealNameCommand());
            $realname->setDescription("view someone's real name");
            $realname->setPermission(Main::PERMISSION_PREFIX."realname");
            $realname->setUsage("/realname <nick>");
            $this->register($realname);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: realname"));
            $this->error($e);
        }

        try{
            $usage = new SimpleCommand("usage", $this->plugin);
            $usage->setExecutor(new UsageCommand());
            $usage->setDescription("Check a command's usage");
            $usage->setPermission(Main::PERMISSION_PREFIX."usage");
            $usage->setAliases(["howtouse"]);
            $usage->setUsage("/usage <command>");
            $this->register($usage);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: usage"));
            $this->error($e);
        }

        try{
            $pt = new SimpleCommand("powertool", $this->plugin);
            $pt->setExecutor(new PowertoolCommand());
            $pt->setDescription("Assign a command to an item");
            $pt->setPermission(Main::PERMISSION_PREFIX."powertool.set");
            $pt->setAliases(["pt"]);
            $pt->setUsage("/powertool <command>");
            $this->register($pt);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: powertool"));
            $this->error($e);
        }

        try{
            $ping = new SimpleCommand("ping", $this->plugin);
            $ping->setExecutor(new PingCommand());
            $ping->setDescription("Pong!");
            $ping->setPermission(Main::PERMISSION_PREFIX."ping");
            $ping->setUsage("/ping");
            $this->register($ping);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: ping"));
            $this->error($e);
        }

        try{
            $fly = new SimpleCommand("fly", $this->plugin);
            $fly->setExecutor(new FlyCommand());
            $fly->setDescription("enable/disable flight");
            $fly->setPermission(Main::PERMISSION_PREFIX."fly.self");
            $fly->setUsage("/fly [player]");
            $this->register($fly);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: fly"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("vanish", $this->plugin);
            $v->setExecutor(new VanishCommand());
            $v->setDescription("enable/disable vanish");
            $v->setPermission(Main::PERMISSION_PREFIX."vanish.self");
            $v->setAliases(["v", "invis"]);
            $v->setUsage("/vanish [player]");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: vanish"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("speed", $this->plugin);
            $v->setExecutor(new SpeedCommand());
            $v->setDescription("change your speed");
            $v->setPermission(Main::PERMISSION_PREFIX."speed.self");
            $v->setAliases(["velocity"]);
            $v->setUsage("/speed <speed> [player]");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: speed"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("xyz", $this->plugin);
            $v->setExecutor(new PosCommand());
            $v->setDescription("show your coordinates");
            $v->setPermission(Main::PERMISSION_PREFIX."xyz.self");
            $v->setAliases(["getpos", "position"]);
            $v->setUsage("/xyz [player]");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: xyz"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("godmode", $this->plugin);
            $v->setExecutor(new GodCommand());
            $v->setDescription("toggle godmode");
            $v->setPermission(Main::PERMISSION_PREFIX."godmode.self");
            $v->setAliases(["god"]);
            $v->setUsage("/godmode [player]");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: godmode"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("nuke", $this->plugin);
            $v->setExecutor(new NukeCommand());
            $v->setDescription("nuke someone");
            $v->setPermission(Main::PERMISSION_PREFIX."nuke.self");
            $v->setUsage("/nuke [player]");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: nuke"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("smite", $this->plugin);
            $v->setExecutor(new ThorCommand());
            $v->setDescription("Thou hast been smitten");
            $v->setPermission(Main::PERMISSION_PREFIX."smite");
            $v->setAliases(["thor", "lightning"]);
            $v->setUsage("/smite");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: smite"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("tree", $this->plugin);
            $v->setExecutor(new TreeCommand());
            $v->setDescription("Spawn a tree");
            $v->setPermission(Main::PERMISSION_PREFIX."tree");
            $v->setUsage("/tree");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: smite"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("break", $this->plugin);
            $v->setExecutor(new BreakCommand());
            $v->setDescription("break the target block");
            $v->setPermission(Main::PERMISSION_PREFIX."break");
            $v->setUsage("/break");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: break"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("thru", $this->plugin);
            $v->setExecutor(new ThruCommand());
            $v->setDescription("go through a block");
            $v->setPermission(Main::PERMISSION_PREFIX."thru");
            $v->setUsage("/thru");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: break"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("tpa", $this->plugin);
            $v->setExecutor(new TpaCommand());
            $v->setDescription("manage teleport requests");
            $v->setPermission(Main::PERMISSION_PREFIX."tpa.command");
            $v->setAliases(["tpahere", "tpaccept", "tpdeny"]);
            $v->setUsage("/tpa <player>");
            $this->register($v);
        }catch (\Throwable $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: tpa"));
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

    private function error(\Throwable $e) : void{
        $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("&cError ".$e->getCode().": ".$e->getMessage()." on line ".$e->getLine()." in file ".$e->getFile()));
    }
}