<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\command\FeedCommand;
use pmessentials\PMEssentials\command\GameModeCommand;
use pmessentials\PMEssentials\command\HealCommand;
use pmessentials\PMEssentials\command\ICommand;
use pmessentials\PMEssentials\command\NickCommand;
use pmessentials\PMEssentials\command\RealNameCommand;
use pmessentials\PMEssentials\command\SizeCommand;
use pmessentials\PMEssentials\command\UsageCommand;
use pocketmine\command\PluginCommand;
use pocketmine\GameMode;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public $api;

	public function onEnable() : void{
	    $this->api = new API($this);

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
	}

	public function onDisable() : void{
	}
}