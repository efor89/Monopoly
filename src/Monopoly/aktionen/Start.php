<?php

namespace Monopoly\aktionen;

use pocketmine\event\{
	Listener,
	player\PlayerInteractEvent
};
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
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
						$gamecfg->set("pasch", 0);
						$gamecfg->set("miete", false);
						$gamecfg->set("wurf", false);
						$gamecfg->set("freiparken", 0);
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
}