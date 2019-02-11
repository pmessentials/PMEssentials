<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\command\FeedCommand;
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

class Main extends PluginBase{

    /** @var API */
    public $api;
    /** @var ModuleManager */
    public $moduleManager;

    public $listeners = [];

	public function onEnable() : void{
	    $this->api = new API($this);
	    $this->moduleManager = new ModuleManager($this);
	    $this->moduleManager->addModule(new PowertoolModule($this));

	    $this->listeners["PowertoolListener"] = new PowertoolListener($this);

        $nick = new PluginCommand("nick", $this);
        $nick->setExecutor(new NickCommand($this, $this->api));
        $nick->setDescription("change your nickname");
        $nick->setPermission("pmessentials.nick");
        $nick->setAliases(["name", "nickname"]);
        $nick->setUsage("/nick [nick] [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $nick, "nick");

        $heal = new PluginCommand("heal", $this);
        $heal->setExecutor(new HealCommand($this, $this->api));
        $heal->setDescription("heal a player");
        $heal->setPermission("pmessentials.heal");
        $heal->setUsage("/heal [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $heal, "heal");

        $feed = new PluginCommand("feed", $this);
        $feed->setExecutor(new FeedCommand($this, $this->api));
        $feed->setDescription("feed a player");
        $feed->setPermission("pmessentials.feed");
        $feed->setUsage("/feed [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $feed, "feed");

        $pmgm = $this->getServer()->getCommandMap()->getCommand("gamemode");
        $this->getServer()->getCommandMap()->unregister($pmgm);

        $gm = new PluginCommand("gamemode", $this);
        $gm->setExecutor(new GameModeCommand($this, $this->api));
        $gm->setDescription("change your gamemode");
        $gm->setPermission("pmessentials.gamemode");
        $gm->setAliases(["gm", "gms", "gmc", "gma", "gmspc", "gmv"]);
        $gm->setUsage("/gamemode <mode> [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $gm, "gamemode");

        $i = new PluginCommand("i", $this);
        $i->setExecutor(new ICommand($this, $this->api));
        $i->setDescription("gives you an item");
        $i->setPermission("pmessentials.i");
        $i->setUsage("/i <item>:[meta] [count]");
        $this->getServer()->getCommandMap()->register("pmessentials", $i, "i");

        $size = new PluginCommand("size", $this);
        $size->setExecutor(new SizeCommand($this, $this->api));
        $size->setDescription("resize a player");
        $size->setPermission("pmessentials.size");
        $size->setAliases(["scale"]);
        $size->setUsage("/size [size] [player]");
        $this->getServer()->getCommandMap()->register("pmessentials", $size, "size");

        $realname = new PluginCommand("realname", $this);
        $realname->setExecutor(new RealNameCommand($this, $this->api));
        $realname->setDescription("view someone's real name");
        $realname->setPermission("pmessentials.realname");
        $realname->setUsage("/realname <nick>");
        $this->getServer()->getCommandMap()->register("pmessentials", $realname, "realname");

        $usage = new PluginCommand("usage", $this);
        $usage->setExecutor(new UsageCommand($this, $this->api));
        $usage->setDescription("Check a command's usage");
        $usage->setPermission("pmessentials.usage");
        $usage->setAliases(["howtouse"]);
        $usage->setUsage("/usage <command>");
        $this->getServer()->getCommandMap()->register("pmessentials", $usage, "usage");

        $pt = new PluginCommand("powertool", $this);
        $pt->setExecutor(new PowertoolCommand($this, $this->api));
        $pt->setDescription("Assign a command to an item");
        $pt->setPermission("pmessentials.powertool.set");
        $pt->setAliases(["pt"]);
        $pt->setUsage("/powertool <command>");
        $this->getServer()->getCommandMap()->register("pmessentials", $pt, "powertool");

        $ping = new PluginCommand("ping", $this);
        $ping->setExecutor(new PingCommand($this, $this->api));
        $ping->setDescription("Pong!");
        $ping->setPermission("pmessentials.ping");
        $ping->setUsage("/ping");
        $this->getServer()->getCommandMap()->register("pmessentials", $ping, "ping");
	}

	public function onDisable() : void{
	}
}