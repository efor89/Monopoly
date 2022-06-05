<?php

namespace Monopoly\aktionen;

use pocketmine\event\{
	Listener,
	player\PlayerInteractEvent
};
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\math\AxisAlignedBB;
use Monopoly\Main;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class Start implements Listener{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
  
    public function onInteract(PlayerInteractEvent $ev){
        $p = $ev->getPlayer();
		$name = $p->getName();
        $item = $ev->getItem();
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		$players = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		$Player1 = $players->get("player1");
		$Player2 = $players->get("player2");
		$Player3 = $players->get("player3");
		$Player4 = $players->get("player4");
		$player1 = Server::getInstance()->getPlayer($Player1);
	   	$player2 = Server::getInstance()->getPlayer($Player2);
	    if($Player3 !== null){
            $player3 = Server::getInstance()->getPlayer($Player3);
	    }
	    if($Player4 !== null){
	        $player4 = Server::getInstance()->getPlayer($Player4);
		}
		if($item->getId() === 399) {
            if($item->getName() === "§aSpiel Starten") {
				if($gamecfg->get("start") !== true){
			        if(count(Server::getInstance()->getOnlinePlayers()) > 1){
						if(($Player1 == null and $Player2 == null and $Player3 == null and $Player4 == null) or ($Player1 == null and $Player2 == null and $Player3 == null) or ($Player1 == null and $Player2 == null and $Player4 == null) or ($Player1 == null and $Player3 == null and $Player4 == null) or ($Player2 == null and $Player3 == null and $Player4 == null)){
							$p->sendMessage("§bMono§6poly: §cEs sind zu wenige Spieler Angemeldet um ein Spiel zu starten.");
							return;
						}
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat das Spiel gestartet.");
					    $player1->getInventory()->clearAll();
					    $player2->getInventory()->clearAll();
						$y = 5;
						$x1 = $config->getNested("coords1.1x");
						$z1 = $config->getNested("coords1.1z");
						$x2 = $config->getNested("coords2.1x");
						$z2 = $config->getNested("coords2.1z");
						$x3 = $config->getNested("coords3.1x");
						$z3 = $config->getNested("coords3.1z");
						$x4 = $config->getNested("coords4.1x");
						$z4 = $config->getNested("coords4.1z");
						$big = new AxisAlignedBB(196, 5, 245, 256, 7, 305);
                        $small = new AxisAlignedBB(203, 5, 252, 248, 7, 297);
						$this->clearRectangle($p->getLevel(), $big, $small);
						$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
						$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
					    if($Player3 != null){
					        $player3->getInventory()->clearAll();
							$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
					    }
					    if($Player4 != null){
					        $player4->getInventory()->clearAll();
							$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
					    }
					    $gamecfg->set("start", true);
						$gamecfg->save();
						$wuerfeln = Item::get(236, 0, 1);
                        $wuerfeln->setCustomName("§aWürfeln");
                        $kaufen = Item::get(266, 0, 1);
                        $kaufen->setCustomName("§6Kaufen");
                        $bauen = Item::get(277, 0, 1);
                        $bauen->setCustomName("§bHaus/Hotel Bauen/Abbauen");		
                        $hypo = Item::get(46, 0, 1);
                        $hypo->setCustomName("§eHypothek");
		                $handeln = Item::get(54, 0, 1);
                        $handeln->setCustomName("§dHandeln");
						$endturn = Item::get(208, 0, 1);
                        $endturn->setCustomName("§3Zug Beenden");
		                $info = Item::get(340, 0, 1);
                        $info->setCustomName("§7Infos");
		                $giveup = Item::get(355, 14, 1);
                        $giveup->setCustomName("§cAufgeben/Bankrott");
						$pay = Item::get(371, 0, 1);
                        $pay->setCustomName("§6Miete Bezahlen");
						if($Player1 != null and $Player2 != null and $Player3 != null and $Player4 != null){
						    $zufallplayer = mt_rand(1, 4);
						}elseif($Player1 != null and $Player2 != null and $Player3 == null and $Player4 == null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 != null and $Player2 == null and $Player3 != null and $Player4 == null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 != null and $Player2 == null and $Player3 == null and $Player4 != null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 != null and $Player2 != null and $Player3 != null and $Player4 == null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 != null and $Player2 != null and $Player3 == null and $Player4 != null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 != null and $Player2 == null and $Player3 != null and $Player4 != null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 == null and $Player2 != null and $Player3 != null and $Player4 == null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 == null and $Player2 != null and $Player3 == null and $Player4 != null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 == null and $Player2 != null and $Player3 != null and $Player4 != null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 == null and $Player2 == null and $Player3 != null and $Player4 != null){
							$zufallplayer = mt_rand(1, 2);
						}
						if($zufallplayer < 2){
						    $gamecfg->set("turn", $player1->getName());
							$gamecfg->save();
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §aist als erster mit Würfeln dran.");
							$player1->getInventory()->setItem(0, $wuerfeln);
                            $player1->getInventory()->setItem(1, $kaufen);
                            $player1->getInventory()->setItem(2, $bauen);
		                    $player1->getInventory()->setItem(3, $hypo);
                            $player1->getInventory()->setItem(4, $handeln);
							$player1->getInventory()->setItem(5, $pay);
						    $player1->getInventory()->setItem(6, $endturn);	                    
						}elseif($zufallplayer < 3){
							$gamecfg->set("turn", $player2->getName());
							$gamecfg->save();
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §aist als erster mit Würfeln dran.");
							$player2->getInventory()->setItem(0, $wuerfeln);
                            $player2->getInventory()->setItem(1, $kaufen);
                            $player2->getInventory()->setItem(2, $bauen);
		                    $player2->getInventory()->setItem(3, $hypo);
                            $player2->getInventory()->setItem(4, $handeln);
							$player2->getInventory()->setItem(5, $pay);
		                    $player2->getInventory()->setItem(6, $endturn);	                    
						}elseif($zufallplayer < 4){
							$gamecfg->set("turn", $player3->getName());
							$gamecfg->save();
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §aist als erster mit Würfeln dran.");
							$player3->getInventory()->setItem(0, $wuerfeln);
                            $player3->getInventory()->setItem(1, $kaufen);
                            $player3->getInventory()->setItem(2, $bauen);
		                    $player3->getInventory()->setItem(3, $hypo);
                            $player3->getInventory()->setItem(4, $handeln);
							$player3->getInventory()->setItem(5, $pay);
		                    $player3->getInventory()->setItem(6, $endturn);   
						}elseif($zufallplayer > 3){
							$gamecfg->set("turn", $player4->getName());
							$gamecfg->save();
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §aist als erster mit Würfeln dran.");
							$player4->getInventory()->setItem(0, $wuerfeln);
                            $player4->getInventory()->setItem(1, $kaufen);
                            $player4->getInventory()->setItem(2, $bauen);
		                    $player4->getInventory()->setItem(3, $hypo);
                            $player4->getInventory()->setItem(4, $handeln);
							$player4->getInventory()->setItem(5, $pay);
		                    $player4->getInventory()->setItem(6, $endturn);
						}
						$player1->getInventory()->setItem(7, $info);
						$player2->getInventory()->setItem(7, $info);
						$player3->getInventory()->setItem(7, $info);
						$player4->getInventory()->setItem(7, $info);
						$player1->getInventory()->setItem(8, $giveup);
						$player2->getInventory()->setItem(8, $giveup);
						$player3->getInventory()->setItem(8, $giveup);
						$player4->getInventory()->setItem(8, $giveup);
						$gamecfg->set("player1", 1);
						$gamecfg->set("player2", 1);
						$gamecfg->set("player3", 1);
						$gamecfg->set("player4", 1);
						$gamecfg->set("bieter1", false);
						$gamecfg->set("bieter2", false);
						$gamecfg->set("bieter3", false);
						$gamecfg->set("bieter4", false);
						$gamecfg->set("pasch", 0);
						$gamecfg->set("miete", false);
						$gamecfg->set("wurf", false);
						$gamecfg->set("freiparken", 0);
						$gamecfg->set("gebot", 0);
						$gamecfg->set("lastg", mt_rand(1, 16));
						$gamecfg->set("laste", mt_rand(1, 16));
						$gamecfg->set("lastpoints", 0);
						$gamecfg->set("knast-turn1", 0);
						$gamecfg->set("knast-turn2", 0);
						$gamecfg->set("knast-turn3", 0);
						$gamecfg->set("knast-turn4", 0);
						$gamecfg->set("knast1", false);
						$gamecfg->set("knast2", false);
						$gamecfg->set("knast3", false);
						$gamecfg->set("knast4", false);
						$gamecfg->set("2", null);
		                $gamecfg->set("4", null);
		                $gamecfg->set("6", null);
		                $gamecfg->set("7", null);
		                $gamecfg->set("9", null);
		                $gamecfg->set("10", null);
		                $gamecfg->set("12", null);
		                $gamecfg->set("13", null);
		                $gamecfg->set("14", null);
		                $gamecfg->set("15", null);
		                $gamecfg->set("16", null);
		                $gamecfg->set("17", null);
		                $gamecfg->set("19", null);
		                $gamecfg->set("20", null);
		                $gamecfg->set("22", null);
		                $gamecfg->set("24", null);
		                $gamecfg->set("25", null);
		                $gamecfg->set("26", null);
		                $gamecfg->set("27", null);
		                $gamecfg->set("28", null);
		                $gamecfg->set("29", null);
		                $gamecfg->set("30", null);
		                $gamecfg->set("32", null);
		                $gamecfg->set("33", null);
		                $gamecfg->set("35", null);
		                $gamecfg->set("36", null);
		                $gamecfg->set("38", null);
		                $gamecfg->set("40", null);
						$gamecfg->set("2hypo", false);
		                $gamecfg->set("4hypo", false);
		                $gamecfg->set("6hypo", false);
		                $gamecfg->set("7hypo", false);
		                $gamecfg->set("9hypo", false);
		                $gamecfg->set("10hypo", false);
		                $gamecfg->set("12hypo", false);
		                $gamecfg->set("13hypo", false);
		                $gamecfg->set("14hypo", false);
		                $gamecfg->set("15hypo", false);
		                $gamecfg->set("16hypo", false);
		                $gamecfg->set("17hypo", false);
		                $gamecfg->set("19hypo", false);
		                $gamecfg->set("20hypo", false);
		                $gamecfg->set("22hypo", false);
		                $gamecfg->set("24hypo", false);
		                $gamecfg->set("25hypo", false);
		                $gamecfg->set("26hypo", false);
		                $gamecfg->set("27hypo", false);
		                $gamecfg->set("28hypo", false);
		                $gamecfg->set("29hypo", false);
		                $gamecfg->set("30hypo", false);
		                $gamecfg->set("32hypo", false);
		                $gamecfg->set("33hypo", false);
		                $gamecfg->set("35hypo", false);
		                $gamecfg->set("36hypo", false);
		                $gamecfg->set("38hypo", false);
		                $gamecfg->set("40hypo", false);
						$gamecfg->save();
						$block = Block::get(236, 0);
						$x1 = $config->getNested("2.bx1");
						$z1 = $config->getNested("2.bz1");
						$x2 = $config->getNested("2.bx2");
						$z2 = $config->getNested("2.bz2");
						$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), $block);
						$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), $block);
						$x3 = $config->getNested("4.bx1");
						$z3 = $config->getNested("4.bz1");
						$x4 = $config->getNested("4.bx2");
						$z4 = $config->getNested("4.bz2");
						$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), $block);
						$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), $block);
						$x5 = $config->getNested("6.bx1");
						$z5 = $config->getNested("6.bz1");
						$x6 = $config->getNested("6.bx2");
						$z6 = $config->getNested("6.bz2");
						$p->getLevel()->setBlock(new Vector3($x5, $y, $z5), $block);
						$p->getLevel()->setBlock(new Vector3($x6, $y, $z6), $block);
						$x7 = $config->getNested("7.bx1");
						$z7 = $config->getNested("7.bz1");
						$x8 = $config->getNested("7.bx2");
						$z8 = $config->getNested("7.bz2");
						$p->getLevel()->setBlock(new Vector3($x7, $y, $z7), $block);
						$p->getLevel()->setBlock(new Vector3($x8, $y, $z8), $block);
						$x9 = $config->getNested("9.bx1");
						$z9 = $config->getNested("9.bz1");
						$x10 = $config->getNested("10.bx2");
						$z10 = $config->getNested("10.bz2");
						$p->getLevel()->setBlock(new Vector3($x9, $y, $z9), $block);
						$p->getLevel()->setBlock(new Vector3($x10, $y, $z10), $block);
						$x11 = $config->getNested("12.bx1");
						$z11 = $config->getNested("12.bz1");
						$x12 = $config->getNested("13.bx2");
						$z12 = $config->getNested("13.bz2");
						$p->getLevel()->setBlock(new Vector3($x11, $y, $z11), $block);
						$p->getLevel()->setBlock(new Vector3($x12, $y, $z12), $block);
						$x13 = $config->getNested("14.bx1");
						$z13 = $config->getNested("14.bz1");
						$x14 = $config->getNested("15.bx2");
						$z14 = $config->getNested("15.bz2");
						$p->getLevel()->setBlock(new Vector3($x13, $y, $z13), $block);
						$p->getLevel()->setBlock(new Vector3($x14, $y, $z14), $block);
						$x15 = $config->getNested("16.bx1");
						$z15 = $config->getNested("16.bz1");
						$x16 = $config->getNested("17.bx2");
						$z16 = $config->getNested("17.bz2");
						$p->getLevel()->setBlock(new Vector3($x15, $y, $z15), $block);
						$p->getLevel()->setBlock(new Vector3($x16, $y, $z16), $block);
						$x17 = $config->getNested("19.bx1");
						$z17 = $config->getNested("19.bz1");
						$x18 = $config->getNested("20.bx2");
						$z18 = $config->getNested("20.bz2");
						$p->getLevel()->setBlock(new Vector3($x17, $y, $z17), $block);
						$p->getLevel()->setBlock(new Vector3($x18, $y, $z18), $block);
						$x19 = $config->getNested("22.bx1");
						$z19 = $config->getNested("22.bz1");
						$x20 = $config->getNested("24.bx2");
						$z20 = $config->getNested("24.bz2");
						$p->getLevel()->setBlock(new Vector3($x19, $y, $z19), $block);
						$p->getLevel()->setBlock(new Vector3($x20, $y, $z20), $block);
						$x21 = $config->getNested("25.bx1");
						$z21 = $config->getNested("25.bz1");
						$x22 = $config->getNested("26.bx2");
						$z22 = $config->getNested("26.bz2");
						$p->getLevel()->setBlock(new Vector3($x21, $y, $z21), $block);
						$p->getLevel()->setBlock(new Vector3($x22, $y, $z22), $block);
						$x23 = $config->getNested("27.bx1");
						$z23 = $config->getNested("27.bz1");
						$x24 = $config->getNested("28.bx2");
						$z24 = $config->getNested("28.bz2");
						$p->getLevel()->setBlock(new Vector3($x23, $y, $z23), $block);
						$p->getLevel()->setBlock(new Vector3($x24, $y, $z24), $block);
						$x25 = $config->getNested("27.bx1");
						$z25 = $config->getNested("27.bz1");
						$x26 = $config->getNested("28.bx2");
						$z26 = $config->getNested("28.bz2");
						$p->getLevel()->setBlock(new Vector3($x25, $y, $z25), $block);
						$p->getLevel()->setBlock(new Vector3($x26, $y, $z26), $block);
						$x27 = $config->getNested("29.bx1");
						$z27 = $config->getNested("29.bz1");
						$x28 = $config->getNested("30.bx2");
						$z28 = $config->getNested("30.bz2");
						$p->getLevel()->setBlock(new Vector3($x27, $y, $z27), $block);
						$p->getLevel()->setBlock(new Vector3($x28, $y, $z28), $block);
						$x29 = $config->getNested("32.bx1");
						$z29 = $config->getNested("32.bz1");
						$x30 = $config->getNested("33.bx2");
						$z30 = $config->getNested("33.bz2");
						$p->getLevel()->setBlock(new Vector3($x29, $y, $z29), $block);
						$p->getLevel()->setBlock(new Vector3($x30, $y, $z30), $block);
						$x31 = $config->getNested("35.bx1");
						$z31 = $config->getNested("35.bz1");
						$x32 = $config->getNested("36.bx2");
						$z32 = $config->getNested("36.bz2");
						$p->getLevel()->setBlock(new Vector3($x31, $y, $z31), $block);
						$p->getLevel()->setBlock(new Vector3($x32, $y, $z32), $block);
						$x33 = $config->getNested("38.bx1");
						$z33 = $config->getNested("38.bz1");
						$x34 = $config->getNested("40.bx2");
						$z34 = $config->getNested("40.bz2");
						$p->getLevel()->setBlock(new Vector3($x33, $y, $z33), $block);
						$p->getLevel()->setBlock(new Vector3($x34, $y, $z34), $block);
					}else{
						$p->sendMessage("§bMono§6poly: §cEs fehlen noch Spieler um ein Spiel zu Starten!");
					}
				}else{
					$p->sendMessage("§bMono§6poly: §cEs läuft grade ein Spiel, du kannst aber gern zuschauen!");
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
	
	function clearRectangle(Level $level, AxisAlignedBB $big, AxisAlignedBB $small): void {
    for ($x = $big->minX; $x < $big->maxX; $x++) {
        for ($z = $big->minZ; $z < $big->maxZ; $z++) {
            if ($x >= $small->minX and $x <= $small->maxX and $z >= $small->minZ and $z <= $small->maxZ) continue;
            for ($y = $big->minY; $y < $big->maxY; $y++) {
                $level->setBlock(new Vector3($x, $y, $z), Block::get(0, 0));
            }
        }
    }
}
}