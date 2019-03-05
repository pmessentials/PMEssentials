<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials\command;

use pmessentials\PMEssentials\API;
use pmessentials\PMEssentials\event\PlayerHealEvent;
use pmessentials\PMEssentials\event\ThorEvent;
use pmessentials\PMEssentials\event\TreeEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\block\Sapling;
use pocketmine\command\Command as pmCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\level\ChunkManager;
use pocketmine\level\Explosion;
use pocketmine\level\generator\object\BigTree;
use pocketmine\level\generator\object\BirchTree;
use pocketmine\level\generator\object\JungleTree;
use pocketmine\level\generator\object\OakTree;
use pocketmine\level\generator\object\SpruceTree;
use pocketmine\level\generator\populator\Tree;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;

class TreeCommand extends SimpleExecutor {

    public function onCommand(CommandSender $sender, pmCommand $command, string $label, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::colorize("&4Sender needs to be a player."));
            return true;
        }
        $type = Sapling::OAK;
        if(isset($args[0])){
            switch (strtolower($args[0])){
                case Sapling::OAK:
                case "oak":
                    $type = Sapling::OAK;
                    break;
                case Sapling::SPRUCE:
                case "spruce":
                    $type = Sapling::SPRUCE;
                    break;
                case Sapling::BIRCH:
                case "birch":
                    $type = Sapling::BIRCH;
                    break;
                case Sapling::JUNGLE:
                case "jungle":
                    $type = Sapling::JUNGLE;
                    break;
                default:
                    $sender->sendMessage(TextFormat::colorize("&4Invalid tree type given!"));
            }
        }
        $ev = new TreeEvent($sender, $sender->getTargetBlock(100));
        $ev->call();
        if($ev->isCancelled()){
            return true;
        }
        $this->growTree($ev->getPosition()->getLevel(), $ev->getPosition()->getFloorX(), $ev->getPosition()->getFloorY()+1, $ev->getPosition()->getFloorZ(), new Random(), $type);
        $sender->sendMessage(TextFormat::colorize("&6Spawned tree"));
        return true;
    }

    public function growTree(ChunkManager $level, int $x, int $y, int $z, Random $random, int $type = 0){
        switch($type){
            case Sapling::SPRUCE:
                $tree = new SpruceTree();
                break;
            case Sapling::BIRCH:
                if($random->nextBoundedInt(39) === 0){
                    $tree = new BirchTree(true);
                }else{
                    $tree = new BirchTree();
                }
                break;
            case Sapling::JUNGLE:
                $tree = new JungleTree();
                break;
            default:
                $tree = new OakTree();
                break;
        }
            $tree->placeObject($level, $x, $y, $z, $random);
    }
}