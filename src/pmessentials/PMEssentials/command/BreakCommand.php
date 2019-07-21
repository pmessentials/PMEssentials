<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerHealEvent;
use pmessentials\PMEssentials\event\ThorEvent;
use pmessentials\PMEssentials\event\TreeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\block\BlockFactory;
use pocketmine\block\Sapling;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\ItemFactory;
use pocketmine\level\ChunkManager;
use pocketmine\level\Explosion;
use pocketmine\level\generator\object\BigTree;
use pocketmine\level\generator\object\BirchTree;
use pocketmine\level\generator\object\JungleTree;
use pocketmine\level\generator\object\OakTree;
use pocketmine\level\generator\object\SpruceTree;
use pocketmine\level\generator\populator\Tree;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\Player;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;

class BreakCommand extends SimpleExecutor {

    public function __construct(){
        parent::__construct();
        $this->name = "break";
        $this->description = "break a block";
        $this->permission = Main::PERMISSION_PREFIX."break";
        $this->usage = "/break";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player."));
            return true;
        }
        $ev = new BlockBreakEvent($sender, $sender->getTargetBlock(100), ItemFactory::get(0), true, []);
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $block = $ev->getBlock();
        if($block->getId() == 0){
            $sender->sendMessage(TextFormat::colorize("&4Cannot break air!"));
            return true;
        }
        $block->getLevel()->setBlockIdAt($block->getFloorX(), $block->getFloorY(), $block->getFloorZ(), 0);
        $sender->sendMessage(TextFormat::colorize("&6Broke the block you're looking at."));
        return true;
    }
}
