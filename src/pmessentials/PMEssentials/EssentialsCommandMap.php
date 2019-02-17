<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

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
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class EssentialsCommandMap{

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

        try{
            $nick = new PluginCommand("nick", $this->plugin);
            $nick->setExecutor(new NickCommand($this->plugin, self::$api));
            $nick->setDescription("change your nickname");
            $nick->setPermission("pmessentials.nick");
            $nick->setAliases(["name", "nickname"]);
            $nick->setUsage("/nick [nick] [player]");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $nick, "nick");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: nick"));
            $this->error($e);
        }


        try{
            $heal = new PluginCommand("heal", $this->plugin);
            $heal->setExecutor(new HealCommand($this->plugin, self::$api));
            $heal->setDescription("heal a player");
            $heal->setPermission("pmessentials.heal");
            $heal->setUsage("/heal [player]");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $heal, "heal");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: heal"));
            $this->error($e);
        }

        try{
            $feed = new PluginCommand("feed", $this->plugin);
            $feed->setExecutor(new FeedCommand($this->plugin, self::$api));
            $feed->setDescription("feed a player");
            $feed->setPermission("pmessentials.feed");
            $feed->setUsage("/feed [player]");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $feed, "feed");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: feed"));
            $this->error($e);
        }

        try{
            $pmgm = $this->plugin->getServer()->getCommandMap()->getCommand("gamemode");
            $this->plugin->getServer()->getCommandMap()->unregister($pmgm);

            $gm = new PluginCommand("gamemode", $this->plugin);
            $gm->setExecutor(new GameModeCommand($this->plugin, self::$api));
            $gm->setDescription("change your gamemode");
            $gm->setPermission("pmessentials.gamemode");
            $gm->setAliases(["gm", "gms", "gmc", "gma", "gmspc", "gmv"]);
            $gm->setUsage("/gamemode <mode> [player]");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $gm, "gamemode");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: gamemode"));
            $this->error($e);
        }

        try{
            $i = new PluginCommand("i", $this->plugin);
            $i->setExecutor(new ICommand($this->plugin, self::$api));
            $i->setDescription("gives you an item");
            $i->setPermission("pmessentials.i");
            $i->setUsage("/i <item>:[meta] [count]");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $i, "i");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: i"));
            $this->error($e);
        }

        try{
            $size = new PluginCommand("size", $this->plugin);
            $size->setExecutor(new SizeCommand($this->plugin, self::$api));
            $size->setDescription("resize a player");
            $size->setPermission("pmessentials.size");
            $size->setAliases(["scale"]);
            $size->setUsage("/size [size] [player]");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $size, "size");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: size"));
            $this->error($e);
        }

        try{
            $realname = new PluginCommand("realname", $this->plugin);
            $realname->setExecutor(new RealNameCommand($this->plugin, self::$api));
            $realname->setDescription("view someone's real name");
            $realname->setPermission("pmessentials.realname");
            $realname->setUsage("/realname <nick>");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $realname, "realname");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: realname"));
            $this->error($e);
        }

        try{
            $usage = new PluginCommand("usage", $this->plugin);
            $usage->setExecutor(new UsageCommand($this->plugin, self::$api));
            $usage->setDescription("Check a command's usage");
            $usage->setPermission("pmessentials.usage");
            $usage->setAliases(["howtouse"]);
            $usage->setUsage("/usage <command>");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $usage, "usage");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: usage"));
            $this->error($e);
        }

        try{
            $pt = new PluginCommand("powertool", $this->plugin);
            $pt->setExecutor(new PowertoolCommand($this->plugin, self::$api));
            $pt->setDescription("Assign a command to an item");
            $pt->setPermission("pmessentials.powertool.set");
            $pt->setAliases(["pt"]);
            $pt->setUsage("/powertool <command>");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $pt, "powertool");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: powertool"));
            $this->error($e);
        }

        try{
            $ping = new PluginCommand("ping", $this->plugin);
            $ping->setExecutor(new PingCommand($this->plugin, self::$api));
            $ping->setDescription("Pong!");
            $ping->setPermission("pmessentials.ping");
            $ping->setUsage("/ping");
            $this->plugin->getServer()->getCommandMap()->register("pmessentials", $ping, "ping");
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: ping"));
            $this->error($e);
        }

        try{
            $fly = new PluginCommand("fly", $this->plugin);
            $fly->setExecutor(new FlyCommand($this->plugin, self::$api));
            $fly->setDescription("enable/disable flight");
            $fly->setPermission("pmessentials.fly");
            $fly->setUsage("/fly [player]");
            $this->register($fly);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: fly"));
            $this->error($e);
        }
    }

    public function register(Command $command) : void{
        $this->commands[$command->getName()] = $command;
        $this->plugin->getServer()->getCommandMap()->register("pmessentials", $command, $command->getName());
    }

    public function error(\Error $e) : void{
        $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("&cError ".$e->getCode().": ".$e->getMessage()." on line ".$e->getLine()." in file ".$e->getFile()));
    }
}