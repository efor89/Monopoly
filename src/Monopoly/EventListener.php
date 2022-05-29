<?php

namespace Monopoly;

use pocketmine\event\{
	Listener,
	block\BlockPlaceEvent,
	block\BlockBreakEvent,
	player\PlayerJoinEvent,
	player\PlayerQuitEvent,
	player\PlayerMoveEvent,
	player\PlayerJumpEvent,
	player\PlayerDeathEvent,
	player\PlayerChatEvent,
	player\PlayerExhaustEvent,
	player\PlayerDropItemEvent,
	player\PlayerInteractEvent,
	entity\EntityDeathEvent,
	entity\EntityDamageByEntityEvent,
	entity\EntityDamageEvent,
	inventory\InventoryTransactionEvent
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

class EventListener implements Listener{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onJoin(PlayerJoinEvent $ev){
        $p = $ev->getPlayer();
        $p->getInventory()->clearAll();
        $anmelden = Item::get(421, 0, 1);
        $anmelden->setCustomName("§aAls Spieler Anmelden");
        $p->getInventory()->setItem(4, $anmelden);
    }
	
	public function onPlayerQuit(PlayerQuitEvent $ev){
		$p = $ev->getPlayer();
		EconomyAPI::getInstance()->setMoney($p, 0);
		$players = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		$gamecfg = new Config($this->plugin->getDataFolder().'game.yml', Config::YAML);
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		$Player1 = $players->get("player1");
		$Player2 = $players->get("player2");
		$Player3 = $players->get("player3");
		$Player4 = $players->get("player4");
		$player1 = Server::getInstance()->getPlayer($Player1);
	   	$player2 = Server::getInstance()->getPlayer($Player2);
	    if($Player3 != null){
            $player3 = Server::getInstance()->getPlayer($Player3);
	    }
	    if($Player4 != null){
	        $player4 = Server::getInstance()->getPlayer($Player4);
		}
		$y = 5;
		$this->plugin->removeCarts($p);
		$this->plugin->removeHypo($p);
		$anmelden = Item::get(421, 0, 1);
        $anmelden->setCustomName("§aAls Spieler Anmelden");
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
		$xlast1 = $config->getNested("coords1.".$gamecfg->get("player1")."x");
	    $zlast1 = $config->getNested("coords1.".$gamecfg->get("player1")."z");
		$xlast2 = $config->getNested("coords2.".$gamecfg->get("player2")."x");
        $zlast2 = $config->getNested("coords2.".$gamecfg->get("player2")."z");
		$xlast3 = $config->getNested("coords3.".$gamecfg->get("player3")."x");
        $zlast3 = $config->getNested("coords3.".$gamecfg->get("player3")."z");
		$xlast4 = $config->getNested("coords4.".$gamecfg->get("player4")."x");
        $zlast4 = $config->getNested("coords4.".$gamecfg->get("player4")."z");
		if($p->getName() != $Player1 and $p->getName() != $Player2 and $p->getName() != $Player3 and $p->getName() != $Player4){
			return;
		}
        if($Player1 != null and $Player2 != null and $Player3 == null and $Player4 == null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
			$gamecfg->set("miete", false);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player2", null);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() != $Player1){
			    $player1->getInventory()->clearAll();
			    $player1->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}else{
				$player2->getInventory()->clearAll();
			    $player2->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}
			$x1 = $config->getNested("coords1.knastx");
		    $z1 = $config->getNested("coords1.knastz");
			$x2 = $config->getNested("coords2.knastx");
			$z2 = $config->getNested("coords2.knastz");
		    $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
		    $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
			$player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		    $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
		}elseif($Player1 != null and $Player2 == null and $Player3 != null and $Player4 == null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
			$gamecfg->set("miete", false);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player3", null);
			$players->save();
			$player2->getInventory()->clearAll();
			$player2->getInventory()->setItem(4, $anmelden);
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() != $Player1){
				$player1->getInventory()->clearAll();
			    $player1->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}else{
				$player3->getInventory()->clearAll();
			    $player3->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}
			$x1 = $config->getNested("coords1.knastx");
		    $z1 = $config->getNested("coords1.knastz");
			$x3 = $config->getNested("coords3.knastx");
			$z3 = $config->getNested("coords3.knastz");
		    $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
		    $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
			$player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		    $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
		}elseif($Player1 != null and $Player2 == null and $Player3 == null and $Player4 != null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
			$gamecfg->set("miete", false);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player4", null);
			$players->save();
			$player2->getInventory()->clearAll();
			$player2->getInventory()->setItem(4, $anmelden);
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() != $Player1){
				$player1->getInventory()->clearAll();
			    $player1->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}else{
				$player4->getInventory()->clearAll();
			    $player4->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}
			$x1 = $config->getNested("coords1.knastx");
		    $z1 = $config->getNested("coords1.knastz");
			$x4 = $config->getNested("coords4.knastx");
			$z4 = $config->getNested("coords4.knastz");
		    $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
		    $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
			$player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		    $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
	    }elseif($Player1 == null and $Player2 != null and $Player3 != null and $Player4 == null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player2", null);
			$players->set("player3", null);
			$gamecfg->set("wurf", false);
			$gamecfg->set("miete", false);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() != $Player2){
				$player2->getInventory()->clearAll();
			    $player2->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}else{
				$player3->getInventory()->clearAll();
			    $player3->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}
			$x3 = $config->getNested("coords3.knastx");
		    $z3 = $config->getNested("coords3.knastz");
			$x2 = $config->getNested("coords2.knastx");
			$z2 = $config->getNested("coords2.knastz");
		    $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
		    $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
			$player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		    $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
		}elseif($Player1 == null and $Player2 != null and $Player3 == null and $Player4 != null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
			$gamecfg->set("miete", false);
		    $gamecfg->save();
			$players->set("player2", null);
			$players->set("player4", null);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() != $Player2){
				$player2->getInventory()->clearAll();
			    $player2->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}else{
				$player4->getInventory()->clearAll();
			    $player4->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}
			$x4 = $config->getNested("coords4.knastx");
		    $z4 = $config->getNested("coords4.knastz");
			$x2 = $config->getNested("coords2.knastx");
			$z2 = $config->getNested("coords2.knastz");
		    $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
		    $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
			$player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		    $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
		}elseif($Player1 == null and $Player2 == null and $Player3 != null and $Player4 != null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
			$gamecfg->set("miete", false);
		    $gamecfg->save();
			$players->set("player3", null);
			$players->set("player4", null);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() != $Player3){
				$player3->getInventory()->clearAll();
			    $player3->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}else{
				$player4->getInventory()->clearAll();
			    $player4->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}
			$x3 = $config->getNested("coords3.knastx");
		    $z3 = $config->getNested("coords3.knastz");
			$x4 = $config->getNested("coords4.knastx");
			$z4 = $config->getNested("coords4.knastz");
		    $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
		    $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
			$player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		    $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
		}
		if($p->getName() == $players->get("player1")){
			$players->set("player1", null);
	        $players->save();
			if($Player2 != null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("turn", $player2->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
					$gamecfg->set("bieter1", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player2->getInventory()->clearAll();
				        $player2->getInventory()->setItem(0, $wuerfeln);
                        $player2->getInventory()->setItem(1, $kaufen);
                        $player2->getInventory()->setItem(2, $bauen);
                        $player2->getInventory()->setItem(3, $hypo);
                        $player2->getInventory()->setItem(4, $handeln);
                        $player2->getInventory()->setItem(6, $endturn);
			            $player2->getInventory()->setItem(7, $info);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}	    
				}else{
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player2 != null and $Player3 == null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("turn", $player2->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player2->getInventory()->clearAll();
				        $player2->getInventory()->setItem(0, $wuerfeln);
                        $player2->getInventory()->setItem(1, $kaufen);
                        $player2->getInventory()->setItem(2, $bauen);
                        $player2->getInventory()->setItem(3, $hypo);
                        $player2->getInventory()->setItem(4, $handeln);
                        $player2->getInventory()->setItem(6, $endturn);
			            $player2->getInventory()->setItem(7, $info);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player2 == null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player3->getInventory()->clearAll();
				        $player3->getInventory()->setItem(0, $wuerfeln);
                        $player3->getInventory()->setItem(1, $kaufen);
                        $player3->getInventory()->setItem(2, $bauen);
                        $player3->getInventory()->setItem(3, $hypo);
                        $player3->getInventory()->setItem(4, $handeln);
                        $player3->getInventory()->setItem(6, $endturn);
			            $player3->getInventory()->setItem(7, $info);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player2 != null and $Player3 != null and $Player4 == null){
				if($p->getName() == $gamecfg->get("turn")){
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("turn", $player2->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player2->getInventory()->clearAll();
				        $player2->getInventory()->setItem(0, $wuerfeln);
                        $player2->getInventory()->setItem(1, $kaufen);
                        $player2->getInventory()->setItem(2, $bauen);
                        $player2->getInventory()->setItem(3, $hypo);
                        $player2->getInventory()->setItem(4, $handeln);
                        $player2->getInventory()->setItem(6, $endturn);
			            $player2->getInventory()->setItem(7, $info);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x1 = $config->getNested("coords1.knastx");
			        $z1 = $config->getNested("coords1.knastz");
			        $player1->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
		            $player1->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(0, 0));
				    $gamecfg->set("bieter1", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}
        }elseif($p->getName() == $players->get("player2")){
	        $players->set("player2", null);
	        $players->save();
			if($Player1 != null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter2", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player3->getInventory()->clearAll();
				        $player3->getInventory()->setItem(0, $wuerfeln);
                        $player3->getInventory()->setItem(1, $kaufen);
                        $player3->getInventory()->setItem(2, $bauen);
                        $player3->getInventory()->setItem(3, $hypo);
                        $player3->getInventory()->setItem(4, $handeln);
                        $player3->getInventory()->setItem(6, $endturn);
			            $player3->getInventory()->setItem(7, $info);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("bieter2", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 != null and $Player3 == null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
					$gamecfg->set("bieter2", false);
					$gamecfg->save();
				    if($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player4->getInventory()->clearAll();
				        $player4->getInventory()->setItem(0, $wuerfeln);
                        $player4->getInventory()->setItem(1, $kaufen);
                        $player4->getInventory()->setItem(2, $bauen);
                        $player4->getInventory()->setItem(3, $hypo);
                        $player4->getInventory()->setItem(4, $handeln);
                        $player4->getInventory()->setItem(6, $endturn);
			            $player4->getInventory()->setItem(7, $info);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("bieter2", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 == null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter2", false);
					$gamecfg->save();
				    if($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player3->getInventory()->clearAll();
				        $player3->getInventory()->setItem(0, $wuerfeln);
                        $player3->getInventory()->setItem(1, $kaufen);
                        $player3->getInventory()->setItem(2, $bauen);
                        $player3->getInventory()->setItem(3, $hypo);
                        $player3->getInventory()->setItem(4, $handeln);
                        $player3->getInventory()->setItem(6, $endturn);
			            $player3->getInventory()->setItem(7, $info);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("bieter2", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 != null and $Player3 != null and $Player4 == null){
				if($p->getName() == $gamecfg->get("turn")){
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter2", false);
					$gamecfg->save();
				    if($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player3->getInventory()->clearAll();
				        $player3->getInventory()->setItem(0, $wuerfeln);
                        $player3->getInventory()->setItem(1, $kaufen);
                        $player3->getInventory()->setItem(2, $bauen);
                        $player3->getInventory()->setItem(3, $hypo);
                        $player3->getInventory()->setItem(4, $handeln);
                        $player3->getInventory()->setItem(6, $endturn);
			            $player3->getInventory()->setItem(7, $info);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x2 = $config->getNested("coords2.knastx");
			        $z2 = $config->getNested("coords2.knastz");
			        $player2->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
		            $player2->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(0, 0));
				    $gamecfg->set("bieter2", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}
        }elseif($p->getName() == $players->get("player3")){
	        $players->set("player3", null);
	        $players->save();
			if($Player1 != null and $Player2 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player4->getInventory()->clearAll();
				        $player4->getInventory()->setItem(0, $wuerfeln);
                        $player4->getInventory()->setItem(1, $kaufen);
                        $player4->getInventory()->setItem(2, $bauen);
                        $player4->getInventory()->setItem(3, $hypo);
                        $player4->getInventory()->setItem(4, $handeln);
                        $player4->getInventory()->setItem(6, $endturn);
			            $player4->getInventory()->setItem(7, $info);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 != null and $Player2 == null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player4->getInventory()->clearAll();
				        $player4->getInventory()->setItem(0, $wuerfeln);
                        $player4->getInventory()->setItem(1, $kaufen);
                        $player4->getInventory()->setItem(2, $bauen);
                        $player4->getInventory()->setItem(3, $hypo);
                        $player4->getInventory()->setItem(4, $handeln);
                        $player4->getInventory()->setItem(6, $endturn);
			            $player4->getInventory()->setItem(7, $info);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 != null and $Player2 != null and $Player4 == null){
				if($p->getName() == $gamecfg->get("turn")){
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player1->getInventory()->clearAll();
				        $player1->getInventory()->setItem(0, $wuerfeln);
                        $player1->getInventory()->setItem(1, $kaufen);
                        $player1->getInventory()->setItem(2, $bauen);
                        $player1->getInventory()->setItem(3, $hypo);
                        $player1->getInventory()->setItem(4, $handeln);
                        $player1->getInventory()->setItem(6, $endturn);
			            $player1->getInventory()->setItem(7, $info);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 == null and $Player2 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter4") == true){
						$player4->getInventory()->clearAll();
					    $player4->getInventory()->setItem(0, $b1);
					    $player4->getInventory()->setItem(1, $b100);
					    $player4->getInventory()->setItem(2, $b1000);
						$player4->getInventory()->setItem(7, $exit);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player4->getInventory()->clearAll();
				        $player4->getInventory()->setItem(0, $wuerfeln);
                        $player4->getInventory()->setItem(1, $kaufen);
                        $player4->getInventory()->setItem(2, $bauen);
                        $player4->getInventory()->setItem(3, $hypo);
                        $player4->getInventory()->setItem(4, $handeln);
                        $player4->getInventory()->setItem(6, $endturn);
			            $player4->getInventory()->setItem(7, $info);
                        $player4->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x3 = $config->getNested("coords3.knastx");
			        $z3 = $config->getNested("coords3.knastz");
			        $player3->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
		            $player3->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(0, 0));
				    $gamecfg->set("bieter3", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}
        }elseif($p->getName() == $players->get("player4")){
	        $players->set("player4", null);
	        $players->save();
			if($Player1 != null and $Player2 != null and $Player3 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player1->getInventory()->clearAll();
				        $player1->getInventory()->setItem(0, $wuerfeln);
                        $player1->getInventory()->setItem(1, $kaufen);
                        $player1->getInventory()->setItem(2, $bauen);
                        $player1->getInventory()->setItem(3, $hypo);
                        $player1->getInventory()->setItem(4, $handeln);
                        $player1->getInventory()->setItem(6, $endturn);
			            $player1->getInventory()->setItem(7, $info);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 != null and $Player2 == null and $Player3 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player1->getInventory()->clearAll();
				        $player1->getInventory()->setItem(0, $wuerfeln);
                        $player1->getInventory()->setItem(1, $kaufen);
                        $player1->getInventory()->setItem(2, $bauen);
                        $player1->getInventory()->setItem(3, $hypo);
                        $player1->getInventory()->setItem(4, $handeln);
                        $player1->getInventory()->setItem(6, $endturn);
			            $player1->getInventory()->setItem(7, $info);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 != null and $Player2 != null and $Player3 == null){
				if($p->getName() == $gamecfg->get("turn")){
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter1") == true){
						$player1->getInventory()->clearAll();
					    $player1->getInventory()->setItem(0, $b1);
					    $player1->getInventory()->setItem(1, $b100);
					    $player1->getInventory()->setItem(2, $b1000);
						$player1->getInventory()->setItem(7, $exit);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player1->getInventory()->clearAll();
				        $player1->getInventory()->setItem(0, $wuerfeln);
                        $player1->getInventory()->setItem(1, $kaufen);
                        $player1->getInventory()->setItem(2, $bauen);
                        $player1->getInventory()->setItem(3, $hypo);
                        $player1->getInventory()->setItem(4, $handeln);
                        $player1->getInventory()->setItem(6, $endturn);
			            $player1->getInventory()->setItem(7, $info);
                        $player1->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}elseif($Player1 == null and $Player2 != null and $Player3 != null){
				if($p->getName() == $gamecfg->get("turn")){
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("turn", $player2->getName());
			    	$gamecfg->set("wurf", false);
					$gamecfg->set("miete", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
                    if($gamecfg->get("bieter2") == true){
						$player2->getInventory()->clearAll();
					    $player2->getInventory()->setItem(0, $b1);
					    $player2->getInventory()->setItem(1, $b100);
					    $player2->getInventory()->setItem(2, $b1000);
						$player2->getInventory()->setItem(7, $exit);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}elseif($gamecfg->get("bieter3") == true){
						$player3->getInventory()->clearAll();
					    $player3->getInventory()->setItem(0, $b1);
					    $player3->getInventory()->setItem(1, $b100);
					    $player3->getInventory()->setItem(2, $b1000);
						$player3->getInventory()->setItem(7, $exit);
                        $player3->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cbietet nicht mehr mit da er den Server verlassen hat.");
					}else{
						$player2->getInventory()->clearAll();
				        $player2->getInventory()->setItem(0, $wuerfeln);
                        $player2->getInventory()->setItem(1, $kaufen);
                        $player2->getInventory()->setItem(2, $bauen);
                        $player2->getInventory()->setItem(3, $hypo);
                        $player2->getInventory()->setItem(4, $handeln);
                        $player2->getInventory()->setItem(6, $endturn);
			            $player2->getInventory()->setItem(7, $info);
                        $player2->getInventory()->setItem(8, $giveup);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
					}
				}else{
					$x4 = $config->getNested("coords4.knastx");
			        $z4 = $config->getNested("coords4.knastz");
			        $player4->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
		            $player4->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(0, 0));
				    $gamecfg->set("bieter4", false);
				    $gamecfg->save();
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chat den Server verlassen hat.");
				}
			}
        }
	}
	
	public function onInventoryTransaction(InventoryTransactionEvent $ev){
        $int = $ev->getTransaction()->getInventories();
        foreach($int as $inst){
            $inst = $inst->getHolder();
            if($inst instanceof Player){
                $p = $inst;
                if(!$p->isOP()){
                    $ev->setCancelled(true);
                }
            }
        }
    }
	
	public function Hunger(PlayerExhaustEvent $ev){
        $ev->setCancelled(true);
    }
	
	
    public function onFallDamage(EntityDamageEvent $ev){
        $ev->setCancelled(true);
    }
	
	public function onBlockPlace(BlockPlaceEvent $ev){
		$p = $ev->getPlayer();
        if(!$p->isOP()){
            $ev->setCancelled(true);
		}
    }
	
	public function onBlockBreak(BlockBreakEvent $ev){
		$p = $ev->getPlayer();
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
    }
}