<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerHealEvent;
use pmessentials\PMEssentials\event\ThorEvent;
use pmessentials\PMEssentials\event\ThruEvent;
use pmessentials\PMEssentials\event\TreeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\block\Block;
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
use pocketmine\math\Vector3;
use pocketmine\math\VoxelRayTrace;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;

class ThruCommand extends SimpleExecutor {

    protected $ignore = [0, 9, 8, 31, 175];

    public function __construct(){
        parent::__construct();
        $this->name = "thru";
        $this->description = "go through a block";
        $this->permission = Main::PERMISSION_PREFIX."thru";
        $this->aliases = ["through", "phase"];
        $this->usage = "/thru";
    }

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player."));
            return true;
        }
        $bool = false;
        $through = null;
        foreach(VoxelRayTrace::inDirection($sender->add(0, $sender->getEyeHeight(), 0), $sender->getDirectionVector(), 30) as $block){
            if(!in_array($sender->getLevel()->getBlockAt($block->x, $block->y, $block->z)->getId(), $this->ignore)){
                $bool = true;
            }elseif($bool === true){
                $through = $sender->getLevel()->getBlockAt($block->x, $block->y, $block->z);
                break;
            }
        }
        if($through instanceof Vector3){
            $ev = new ThruEvent($sender, $through);
            if($ev->isCancelled()){
                return true;
            }
            $sender->teleport($ev->getBlock());
            $sender->sendMessage(TextFormat::colorize("&6Going through the target block."));
        }else{
            $sender->sendMessage(TextFormat::colorize("&4Nothing to go through!"));
        }
        return true;
    }
}