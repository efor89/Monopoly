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
		if($p->getName() === $Player1){
            $gamecfg->set("turn", $player2->getName());
		    $gamecfg->save();
			$player2->getInventory()->setItem(0, $wuerfeln);
            $player2->getInventory()->setItem(1, $kaufen);
            $player2->getInventory()->setItem(2, $bauen);
            $player2->getInventory()->setItem(3, $hypo);
            $player2->getInventory()->setItem(4, $handeln);
            $player2->getInventory()->setItem(6, $endturn);
			$player2->getInventory()->setItem(7, $info);
            $player2->getInventory()->setItem(8, $giveup);
        }elseif($p->getName() === $Player2){
	        $gamecfg->set("turn", $player3->getName());
		    $gamecfg->save();
			$player3->getInventory()->setItem(0, $wuerfeln);
            $player3->getInventory()->setItem(1, $kaufen);
            $player3->getInventory()->setItem(2, $bauen);
            $player3->getInventory()->setItem(3, $hypo);
            $player3->getInventory()->setItem(4, $handeln);
            $player3->getInventory()->setItem(6, $endturn);
			$player3->getInventory()->setItem(7, $info);
            $player3->getInventory()->setItem(8, $giveup);					
        }elseif($p->getName() === $Player3){
	        $gamecfg->set("turn", $player4->getName());
		    $gamecfg->save();
			$player4->getInventory()->setItem(0, $wuerfeln);
            $player4->getInventory()->setItem(1, $kaufen);
            $player4->getInventory()->setItem(2, $bauen);
            $player4->getInventory()->setItem(3, $hypo);
            $player4->getInventory()->setItem(4, $handeln);
            $player4->getInventory()->setItem(6, $endturn);
			$player4->getInventory()->setItem(7, $info);
            $player4->getInventory()->setItem(8, $giveup);
        }elseif($p->getName() === $Player4){
	        $gamecfg->set("turn", $player1->getName());
		    $gamecfg->save();
			$player1->getInventory()->setItem(0, $wuerfeln);
            $player1->getInventory()->setItem(1, $kaufen);
            $player1->getInventory()->setItem(2, $bauen);
            $player1->getInventory()->setItem(3, $hypo);
            $player1->getInventory()->setItem(4, $handeln);
            $player1->getInventory()->setItem(6, $endturn);
			$player1->getInventory()->setItem(7, $info);
            $player1->getInventory()->setItem(8, $giveup);
		}
		if($p->getName() == $players->get("player1")){
			$players->set("player1", null);
			$players->save();
		}elseif($p->getName() == $players->get("player2")){
			$players->set("player2", null);
			$players->save();
		}elseif($p->getName() == $players->get("player3")){
			$players->set("player3", null);
			$players->save();
		}elseif($p->getName() == $players->get("player4")){
			$players->set("player4", null);
			$players->save();
		}
        if($Player1 !== null and $Player2 !== null and $Player3 == null and $Player4 == null){
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player2", null);
			$players->save();
			if($p->getName() === $player1->getName()){
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}else{
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 !== null and $Player2 == null and $Player3 !== null and $Player4 == null){
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player3", null);
			$players->save();
			if($p->getName() === $player1->getName()){
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}else{
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 !== null and $Player2 == null and $Player3 == null and $Player4 !== null){
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player4", null);
			$players->save();
			if($p->getName() === $player1->getName()){
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}else{
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}
	    }elseif($Player1 == null and $Player2 !== null and $Player3 !== null and $Player4 == null){
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player2", null);
			$players->set("player3", null);
			$players->save();
			if($p->getName() === $player2->getName()){
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}else{
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 == null and $Player2 !== null and $Player3 == null and $Player4 !== null){
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player2", null);
			$players->set("player4", null);
			$players->save();
			if($p->getName() === $player2->getName()){
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}else{
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 == null and $Player2 == null and $Player3 !== null and $Player4 !== null){
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player3", null);
			$players->set("player4", null);
			$players->save();
			if($p->getName() === $player3->getName()){
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}else{
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}
		}
	}
	
	public function onInventoryTransaction(InventoryTransactionEvent $ev){
        $int = $ev->getTransaction()->getInventories();
        foreach($int as $inst){
            $inst = $inst->getHolder();
            if($inst instanceof Player){
                $p = $inst;
                if(!$p->hasPermission("bypass.op")){
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
		if($item->getId() === 421) {
            if($item->getName() === "§aAls Spieler Anmelden") {
				if($gamecfg->get("start") !== true){
		            EconomyAPI::getInstance()->setMoney($p, 40000);
		            if($players->get("player1") == null){
			            $players->set("player1", $name);
			            $players->save();
					    $p->getInventory()->clearAll();
                        $anmelden = Item::get(399, 0, 1);
                        $anmelden->setCustomName("§aSpiel Starten");
                        $p->getInventory()->setItem(0, $anmelden);
		            }elseif($players->get("player1") !== null){
			            if($players->get("player2") == null){
				            $players->set("player2", $name);
			                $players->save();
						    $p->getInventory()->clearAll();
                            $anmelden = Item::get(399, 0, 1);
                            $anmelden->setCustomName("§aSpiel Starten");
                            $p->getInventory()->setItem(0, $anmelden);
			            }elseif($players->get("player2") !== null){
				            if($players->get("player3") == null){
				                $players->set("player3", $name);
			                    $players->save();
							    $p->getInventory()->clearAll();
                                $anmelden = Item::get(399, 0, 1);
                                $anmelden->setCustomName("§aSpiel Starten");
                                $p->getInventory()->setItem(0, $anmelden);
			                }elseif($players->get("player3") !== null){
					            if($players->get("player4") == null){
				                    $players->set("player4", $name);
			                        $players->save();
							    	$p->getInventory()->clearAll();
                                    $anmelden = Item::get(399, 0, 1);
                                    $anmelden->setCustomName("§aSpiel Starten");
                                    $p->getInventory()->setItem(0, $anmelden);
			                    }else{
						            $p->sendMessage("§bMono§6poly: §cEs sind Schon 4 Spieler angemeldet. Du kannst aber gern zuschauen.");
					            }
							}
				        }
			        }
		        }else{
					$p->sendMessage("§bMono§6poly: §cEs läuft grade ein Spiel, du kannst aber gern zuschauen!");
				}
			}
		}
		if($item->getId() === 399) {
            if($item->getName() === "§aSpiel Starten") {
				if($gamecfg->get("start") !== true){
			        if(count(Server::getInstance()->getOnlinePlayers()) > 1){
						if(($Player1 == null and $Player2 == null and $Player3 == null and $Player4 == null) or ($Player1 == null and $Player2 == null and $Player3 == null) or ($Player1 == null and $Player2 == null and $Player4 == null) or ($Player1 == null and $Player3 == null and $Player4 == null) or ($Player2 == null and $Player3 == null and $Player4 == null)){
							$p->sendMessage("§bMono§6poly: §cEs sind zu wenige Spieler Angemeldet um ein Spiel zu starten.");
							return;
						}
					    $player1->getInventory()->clearAll();
					    $player2->getInventory()->clearAll();
					    if($Player3 !== null){
					        $player3->getInventory()->clearAll();
					    }
					    if($Player4 !== null){
					        $player4->getInventory()->clearAll();
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
						if($Player1 !== null and $Player2 !== null and $Player3 !== null and $Player4 !== null){
						    $zufallplayer = mt_rand(1, 4);
						}elseif($Player1 !== null and $Player2 !== null and $Player3 == null and $Player4 == null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 !== null and $Player2 == null and $Player3 !== null and $Player4 == null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 !== null and $Player2 == null and $Player3 == null and $Player4 !== null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 !== null and $Player2 !== null and $Player3 !== null and $Player4 == null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 !== null and $Player2 !== null and $Player3 == null and $Player4 !== null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 !== null and $Player2 == null and $Player3 !== null and $Player4 !== null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 == null and $Player2 !== null and $Player3 !== null and $Player4 == null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 == null and $Player2 !== null and $Player3 == null and $Player4 !== null){
							$zufallplayer = mt_rand(1, 2);
						}elseif($Player1 == null and $Player2 !== null and $Player3 !== null and $Player4 !== null){
							$zufallplayer = mt_rand(1, 3);
						}elseif($Player1 == null and $Player2 == null and $Player3 !== null and $Player4 !== null){
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
					}else{
						$p->sendMessage("§bMono§6poly: §cEs fehlen noch Spieler um ein Spiel zu Starten!");
					}
				}else{
					$p->sendMessage("§bMono§6poly: §cEs läuft grade ein Spiel, du kannst aber gern zuschauen!");
				}
            }
        }
		if($item->getId() === 236) {
            if($item->getName() === "§aWürfeln") {
                $point1 = $this->getZufall1();
				$point2 = $this->getZufall2();
			    if($point1 == $point2){
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
					
				}else{
					
					Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt.");
				}
            }
        }
		if($item->getId() === 266) {
            if($item->getName() === "§6Kaufen") {
                $playerMoney = EconomyAPI::getInstance()->myMoney($p);
				$buy = $config->getNested($feld.".buy");
				if($playerMoney > $buy){
					$p->sendMessage("kaufen");
				}
            }
        }
		if($item->getId() === 277) {
            if($item->getName() === "§bHaus/Hotel Bauen/Abbauen") {
				$p->getInventory()->clearAll();
                $haus = Item::get(236, 5, 64);
                $haus->setCustomName("§aHaus Bauen");
				$hotel = Item::get(236, 14, 64);
                $hotel->setCustomName("§aHotel Bauen");
		        $abbauen = Item::get(278, 0, 1);
                $abbauen->setCustomName("§6Abbauen");
				$exit = Item::get(331, 14, 1);
                $exit->setCustomName("§cZurück");
                $p->getInventory()->setItem(0, $haus);
				$p->getInventory()->setItem(1, $hotel);
                $p->getInventory()->setItem(4, $abbauen);
                $p->getInventory()->setItem(8, $exit);
            }
        }
		if($item->getId() === 46) {
            if($item->getName() === "§eHypothek") {
                $p->sendMessage("hypo");
            }
        }
		if($item->getId() === 54) {
            if($item->getName() === "§dHandeln") {
                $p->sendMessage("handeln");
            }
        }
		if($item->getId() === 208) {
            if($item->getName() === "§3Zug Beenden") {
                $p->getInventory()->clearAll();
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
				if($p->getName() === $Player1){
		            $gamecfg->set("turn", $Player2);
				    $gamecfg->save();
					$player2->getInventory()->setItem(0, $wuerfeln);
                    $player2->getInventory()->setItem(1, $kaufen);
                    $player2->getInventory()->setItem(2, $bauen);
                    $player2->getInventory()->setItem(3, $hypo);
                    $player2->getInventory()->setItem(4, $handeln);
                    $player2->getInventory()->setItem(6, $endturn);
					$player2->getInventory()->setItem(7, $info);
                    $player2->getInventory()->setItem(8, $giveup);
					$p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup); 
		        }elseif($p->getName() === $Player2){
			        $gamecfg->set("turn", $Player3);
				    $gamecfg->save();
					$player3->getInventory()->setItem(0, $wuerfeln);
                    $player3->getInventory()->setItem(1, $kaufen);
                    $player3->getInventory()->setItem(2, $bauen);
                    $player3->getInventory()->setItem(3, $hypo);
                    $player3->getInventory()->setItem(4, $handeln);
                    $player3->getInventory()->setItem(6, $endturn);
					$player3->getInventory()->setItem(7, $info);
                    $player3->getInventory()->setItem(8, $giveup);
                    $p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup); 					
		        }elseif($p->getName() === $Player3){
			        $gamecfg->set("turn", $Player4);
				    $gamecfg->save();
					$player4->getInventory()->setItem(0, $wuerfeln);
                    $player4->getInventory()->setItem(1, $kaufen);
                    $player4->getInventory()->setItem(2, $bauen);
                    $player4->getInventory()->setItem(3, $hypo);
                    $player4->getInventory()->setItem(4, $handeln);
                    $player4->getInventory()->setItem(6, $endturn);
					$player4->getInventory()->setItem(7, $info);
                    $player4->getInventory()->setItem(8, $giveup);
                    $p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
		        }elseif($p->getName() === $Player4){
			        $gamecfg->set("turn", $Player1);
				    $gamecfg->save();
					$player1->getInventory()->setItem(0, $wuerfeln);
                    $player1->getInventory()->setItem(1, $kaufen);
                    $player1->getInventory()->setItem(2, $bauen);
                    $player1->getInventory()->setItem(3, $hypo);
                    $player1->getInventory()->setItem(4, $handeln);
                    $player1->getInventory()->setItem(6, $endturn);
					$player1->getInventory()->setItem(7, $info);
                    $player1->getInventory()->setItem(8, $giveup);
  					$p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
		        }
            }
        }
		if($item->getId() === 340) {
            if($item->getName() === "§7Infos") {
                $p->sendMessage("info");
            }
        }
		if($item->getId() === 355) {
            if($item->getName() === "§cAufgeben/Bankrott") {
				$p->getInventory()->clearAll();
                $ja = Item::get(355, 14, 1);
                $ja->setCustomName("§aJa Aufgeben");
				$nein = Item::get(450, 0, 1);
                $nein->setCustomName("§cNein nicht Aufgeben");
                $p->getInventory()->setItem(3, $ja);
				$p->getInventory()->setItem(5, $nein);
            }
        }
		if($item->getId() === 355) {
            if($item->getName() === "§aJa Aufgeben") {
                EconomyAPI::getInstance()->setMoney($p, 0);
				if($p->getName() == $players->get("player1")){
			        $players->set("player1", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(0, $anmelden);
					if($Player2 !== null){
						$gamecfg->set("turn", $player2->getName());
						$gamecfg->save();
					}elseif($Player2 == null){
						$gamecfg->set("turn", $player3->getName());
						$gamecfg->save();
					}
		        }elseif($p->getName() == $players->get("player2")){
			        $players->set("player2", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(0, $anmelden);
					if($Player3 !== null){
						$gamecfg->set("turn", $player3->getName());
						$gamecfg->save();
					}elseif($Player3 == null){
						$gamecfg->set("turn", $player4->getName());
						$gamecfg->save();
					}
		        }elseif($p->getName() == $players->get("player3")){
			        $players->set("player3", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(0, $anmelden);
					if($Player4 !== null){
						$gamecfg->set("turn", $player4->getName());
						$gamecfg->save();
					}elseif($Player4 == null){
						$gamecfg->set("turn", $player1->getName());
						$gamecfg->save();
					}
		        }elseif($p->getName() == $players->get("player4")){
			        $players->set("player4", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(0, $anmelden);
					if($Player1 !== null){
						$gamecfg->set("turn", $player1->getName());
						$gamecfg->save();
					}elseif($Player1 == null){
						$gamecfg->set("turn", $player2->getName());
						$gamecfg->save();
					}
		        }
				foreach(Server::getInstance()->getOnlinePlayers() as $player){
				    if($players->get("player1") == null and $players->get("player2") == null){
					    $players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
					    $player->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() === $player3->getName()){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat das Spiel Gewonnen.");
			            }
				    }elseif($players->get("player1") == null and $players->get("player3") == null){
					    $players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
					    $player->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() === $player4->getName()){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat das Spiel Gewonnen.");
			            }
				    }elseif($players->get("player1") == null and $players->get("player4") == null){
					    $players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
					    $player->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() === $player3->getName()){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat das Spiel Gewonnen.");
			            }
				    }elseif($players->get("player2") == null and $players->get("player3") == null){
					    $players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
					    $player->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() === $player1->getName()){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat das Spiel Gewonnen.");
			            }
				    }elseif($players->get("player2") == null and $players->get("player4") == null){
					    $players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
					    $player->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() === $player1->getName()){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat das Spiel Gewonnen.");
			            }
				    }elseif($players->get("player3") == null and $players->get("player4") == null){
					    $players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
					    $player->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() === $player1->getName()){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat das Spiel Gewonnen.");
			            }
				    }
				}
            }
        }
		if($item->getId() === 450){
            if($item->getName() === "§cNein nicht Aufgeben"){
				$p->getInventory()->clearAll();
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
				if($p->getName() === $gamecfg->get("turn")){	            
					$p->getInventory()->setItem(0, $wuerfeln);
                    $p->getInventory()->setItem(1, $kaufen);
                    $p->getInventory()->setItem(2, $bauen);
                    $p->getInventory()->setItem(3, $hypo);
                    $p->getInventory()->setItem(4, $handeln);
                    $p->getInventory()->setItem(6, $endturn);
		        }else{			
				    $p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
				}
            }
        }
		if($item->getId() === 331) {
            if($item->getName() === "§cZurück") {
                $p->getInventory()->clearAll();
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
                if($p->getName() === $gamecfg->get("turn")){	            
					$p->getInventory()->setItem(0, $wuerfeln);
                    $p->getInventory()->setItem(1, $kaufen);
                    $p->getInventory()->setItem(2, $bauen);
                    $p->getInventory()->setItem(3, $hypo);
                    $p->getInventory()->setItem(4, $handeln);
                    $p->getInventory()->setItem(6, $endturn);
		        }else{			
				    $p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
	
	public function getZufall1(){
		return mt_rand(1, 6);
	}
	
	public function getZufall2(){
		return mt_rand(1, 6);
	}
}