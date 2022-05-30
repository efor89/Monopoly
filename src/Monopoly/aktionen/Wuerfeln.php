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
use Monopoly\ui\Gemeinschaftskarte;
use Monopoly\ui\Ereigniskarte;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class Wuerfeln implements Listener{

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
		if($item->getId() === 236) {
            if($item->getName() === "§aWürfeln") {
                $point1 = $this->plugin->getZufall1();
				$point2 = $this->plugin->getZufall2();
				$points = $point1 + $point2;
				$gamecfg->set("lastpoints", $points);
				$gamecfg->save();
				if($gamecfg->get("wurf") !== true){
					if($p->getName() == $Player1){
						$nummer = $gamecfg->get("player1");
					}elseif($p->getName() == $Player2){
						$nummer = $gamecfg->get("player2");
					}elseif($p->getName() == $Player3){
						$nummer = $gamecfg->get("player3");
					}elseif($p->getName() == $Player4){
						$nummer = $gamecfg->get("player4");
					}
					if($gamecfg->get($nummer) == null){
						if($nummer == 2 or $nummer == 4 or $nummer == 6 or $nummer == 7 or $nummer == 9 or $nummer == 10 or $nummer == 12 or $nummer == 13 or $nummer == 14 or $nummer == 15 or $nummer == 16 or $nummer == 17 or $nummer == 19 or $nummer == 20 or $nummer == 22 or $nummer == 24 or $nummer == 25 or $nummer == 26 or $nummer == 27 or $nummer == 28 or $nummer == 29 or $nummer == 30 or $nummer == 32 or $nummer == 33 or $nummer == 35 or $nummer == 36 or $nummer == 38 or $nummer == 40){
						    $p->sendMessage("§bMono§6poly: §cDu musst die Strasse noch kaufen!");
						    return;
						}
					}
					$y = 5;
			        $x1 = $config->getNested("coords1.".$gamecfg->get("player1") + $points."x");
			        $z1 = $config->getNested("coords1.".$gamecfg->get("player1") + $points."z");
					$xlast1 = $config->getNested("coords1.".$gamecfg->get("player1")."x");
			        $zlast1 = $config->getNested("coords1.".$gamecfg->get("player1")."z");
			        $x2 = $config->getNested("coords2.".$gamecfg->get("player2") + $points."x");
			        $z2 = $config->getNested("coords2.".$gamecfg->get("player2") + $points."z");
					$xlast2 = $config->getNested("coords2.".$gamecfg->get("player2")."x");
			        $zlast2 = $config->getNested("coords2.".$gamecfg->get("player2")."z");
			        $x3 = $config->getNested("coords3.".$gamecfg->get("player3") + $points."x");
			        $z3 = $config->getNested("coords3.".$gamecfg->get("player3") + $points."z");
					$xlast3 = $config->getNested("coords3.".$gamecfg->get("player3")."x");
			        $zlast3 = $config->getNested("coords3.".$gamecfg->get("player3")."z");
			        $x4 = $config->getNested("coords4.".$gamecfg->get("player4") + $points."x");
			        $z4 = $config->getNested("coords4.".$gamecfg->get("player4") + $points."z");
					$xlast4 = $config->getNested("coords4.".$gamecfg->get("player4")."x");
			        $zlast4 = $config->getNested("coords4.".$gamecfg->get("player4")."z");
			        if($point1 == $point2){
						if($gamecfg->get("pasch") < 2){
							if($gamecfg->get("miete") !== false){
								$p->sendMessage("§bMono§6poly: §cDu musst erst Miete bezahlen!");
								return;
							}
							if($p->getName() == $Player1){
							    if($gamecfg->get("knast1") !== false){
									if($gamecfg->get($gamecfg->get("player1") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player1") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player1") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player1") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player1") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									$xlast = $config->getNested("coords1.knastx");
								    $zlast = $config->getNested("coords1.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
								    $gamecfg->set("player1", $gamecfg->get("player1") + $points);
									$gamecfg->set("wurf", true);
									$gamecfg->set("pasch", 0);
									$gamecfg->set("knast1", false);
								    $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kommt er aus dem Gefängnis frei.");
									return;
								}else{
									if($gamecfg->get("player1") + $points <= 40){
										if($gamecfg->get("player1") + $points == 31){
											$x = $config->getNested("coords1.knastx");
											$z = $config->getNested("coords1.knastz");
											$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									        $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
											$gamecfg->set("player1", 11);
											$gamecfg->set("pasch", 0);
											$gamecfg->set("wurf", true);
											$gamecfg->set("knast1", true);
									        $gamecfg->save();
											$p->getInventory()->clearAll();
							                $endturn = Item::get(208, 0, 1);
                                            $endturn->setCustomName("§3Zug Beenden");
							                $p->getInventory()->setItem(6, $endturn);
											Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
											return;
										}
										if($gamecfg->get("player1") + $points == 21){
										    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										    $gamecfg->set("freiparken", 0);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									    }
										if($gamecfg->get("player1") + $points == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									    }
										if($gamecfg->get("player1") + $points == 39){
										    EconomyAPI::getInstance()->reduceMoney($p, 2000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									    }
										if($gamecfg->get("player1") + $points == 3 or $gamecfg->get("player1") + $points == 18 or $gamecfg->get("player1") + $points == 34){
											$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
										}
										if($gamecfg->get("player1") + $points == 8 or $gamecfg->get("player1") + $points == 23 or $gamecfg->get("player1") + $points == 37){
											$this->plugin->getEreignis()->EreignisKarte($p);
										}
										if($gamecfg->get($player1->getName()) + $points !== null){
										    if($gamecfg->get($player1->getName()) + $points != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
									    $p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
										$xlast = $config->getNested("coords1.knastx");
								        $zlast = $config->getNested("coords1.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									    $gamecfg->set("player1", $gamecfg->get("player1") + $points);
									    $gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
									    $gamecfg->save();
									}else{
										if((($gamecfg->get("player1") + $points) - 40) == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									        $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									        $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
								    	}
										if((($gamecfg->get("player1") + $points) - 40) == 3){
										    $this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									    }
									    if((($gamecfg->get("player1") + $points) - 40) == 8){
										    $this->plugin->getEreignis()->EreignisKarte($p);
									    }
										if($gamecfg->get($player1->getName()) + $points - 40 != null){
										    if($gamecfg->get($player1->getName()) + $points - 40 != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."x");
			                            $z = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
										$gamecfg->set("player1", ($gamecfg->get("player1") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
										
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}elseif($p->getName() == $Player2){
							    if($gamecfg->get("knast2") !== false){
									if($gamecfg->get($gamecfg->get("player2") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player2") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player2") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player2") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player2") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									$xlast = $config->getNested("coords2.knastx");
								    $zlast = $config->getNested("coords2.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
								    $gamecfg->set("player2", $gamecfg->get("player2") + $points);
								    $gamecfg->set("wurf", true);
									$gamecfg->set("knast2", false);
								    $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kommt er aus dem Gefängnis frei.");
									return;
								}else{
									if($gamecfg->get("player2") + $points <= 40){
										if($gamecfg->get("player2") + $points == 31){
											$x = $config->getNested("coords2.knastx");
											$z = $config->getNested("coords2.knastz");
											$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									        $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
											$gamecfg->set("player2", 11);
											$gamecfg->set("pasch", 0);
											$gamecfg->set("wurf", true);
											$gamecfg->set("knast2", true);
									        $gamecfg->save();
											$p->getInventory()->clearAll();
							                $endturn = Item::get(208, 0, 1);
                                            $endturn->setCustomName("§3Zug Beenden");
							                $p->getInventory()->setItem(6, $endturn);
											Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
											return;
										}
										if($gamecfg->get("player2") + $points == 21){
										    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										    $gamecfg->set("freiparken", 0);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									    }
										if($gamecfg->get("player2") + $points == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									    }
										if($gamecfg->get("player2") + $points == 39){
										    EconomyAPI::getInstance()->reduceMoney($p, 2000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									    }
										if($gamecfg->get("player2") + $points == 3 or $gamecfg->get("player2") + $points == 18 or $gamecfg->get("player2") + $points == 34){
											$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
										}
										if($gamecfg->get("player2") + $points == 8 or $gamecfg->get("player2") + $points == 23 or $gamecfg->get("player2") + $points == 37){
											$this->plugin->getEreignis()->EreignisKarte($p);
										}
										if($gamecfg->get($player2->getName()) + $points != null){
										    if($gamecfg->get($player2->getName()) + $points != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
									    $p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
										$xlast = $config->getNested("coords2.knastx");
								        $zlast = $config->getNested("coords2.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									    $gamecfg->set("player2", $gamecfg->get("player2") + $points);
									    $gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
									    $gamecfg->save();
									}else{
										if((($gamecfg->get("player2") + $points) - 40) == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									        $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									        $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
								    	}
										if((($gamecfg->get("player2") + $points) - 40) == 3){
										    $this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									    }
									    if((($gamecfg->get("player2") + $points) - 40) == 8){
										    $this->plugin->getEreignis()->EreignisKarte($p);
									    }
										if($gamecfg->get($player2->getName()) + $points - 40 != null){
										    if($gamecfg->get($player2->getName()) + $points - 40 != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."x");
			                            $z = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
										$gamecfg->set("player2", ($gamecfg->get("player2") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}elseif($p->getName() == $Player3){
							    if($gamecfg->get("knast3") !== false){
									if($gamecfg->get($gamecfg->get("player3") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player3") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player3") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player3") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player3") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									$xlast = $config->getNested("coords3.knastx");
								    $zlast = $config->getNested("coords3.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
								    $gamecfg->set("player3", $gamecfg->get("player3") + $points);
								    $gamecfg->set("wurf", true);
									$gamecfg->set("knast3", false);
								    $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kommt er aus dem Gefängnis frei.");
									return;
								}else{
									if($gamecfg->get("player3") + $points <= 40){
										if($gamecfg->get("player3") + $points == 31){
											$x = $config->getNested("coords3.knastx");
											$z = $config->getNested("coords3.knastz");
											$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									        $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
											$gamecfg->set("player3", 11);
											$gamecfg->set("pasch", 0);
											$gamecfg->set("wurf", true);
											$gamecfg->set("knast3", true);
									        $gamecfg->save();
											$p->getInventory()->clearAll();
							                $endturn = Item::get(208, 0, 1);
                                            $endturn->setCustomName("§3Zug Beenden");
							                $p->getInventory()->setItem(6, $endturn);
											Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
											return;
										}
										if($gamecfg->get("player3") + $points == 21){
										    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										    $gamecfg->set("freiparken", 0);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									    }
										if($gamecfg->get("player3") + $points == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									    }
										if($gamecfg->get("player3") + $points == 39){
										    EconomyAPI::getInstance()->reduceMoney($p, 2000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									    }
										if($gamecfg->get("player3") + $points == 3 or $gamecfg->get("player3") + $points == 18 or $gamecfg->get("player3") + $points == 34){
											$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
										}
										if($gamecfg->get("player3") + $points == 8 or $gamecfg->get("player3") + $points == 23 or $gamecfg->get("player3") + $points == 37){
											$this->plugin->getEreignis()->EreignisKarte($p);
										}
										if($gamecfg->get($player3->getName()) + $points != null){
										    if($gamecfg->get($player3->getName()) + $points != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
									    $p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
										$xlast = $config->getNested("coords3.knastx");
								        $zlast = $config->getNested("coords3.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									    $gamecfg->set("player3", $gamecfg->get("player3") + $points);
									    $gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
									    $gamecfg->save();
									}else{
										if((($gamecfg->get("player3") + $points) - 40) == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									        $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									        $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
								    	}
										if((($gamecfg->get("player3") + $points) - 40) == 3){
										    $this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									    }
									    if((($gamecfg->get("player3") + $points) - 40) == 8){
										    $this->plugin->getEreignis()->EreignisKarte($p);
									    }
										if($gamecfg->get($player3->getName()) + $points - 40 != null){
										    if($gamecfg->get($player3->getName()) + $points - 40 != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."x");
			                            $z = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(91, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
										$gamecfg->set("player3", ($gamecfg->get("player3") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}elseif($p->getName() == $Player4){
							    if($gamecfg->get("knast4") !== false){
									if($gamecfg->get($gamecfg->get("player4") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player4") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player4") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player4") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player4") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									$xlast = $config->getNested("coords4.knastx");
								    $zlast = $config->getNested("coords4.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
								    $gamecfg->set("player4", $gamecfg->get("player4") + $points);
									$gamecfg->set("knast4", false);
									$gamecfg->set("wurf", true);
								    $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kommt er aus dem Gefängnis frei.");
									return;
								}else{
									if($gamecfg->get("player4") + $points <= 40){
										if($gamecfg->get("player1") + $points == 31){
											$x = $config->getNested("coords4.knastx");
											$z = $config->getNested("coords4.knastz");
											$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									        $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
											$gamecfg->set("player4", 11);
											$gamecfg->set("pasch", 0);
											$gamecfg->set("wurf", true);
											$gamecfg->set("knast4", true);
									        $gamecfg->save();
											$p->getInventory()->clearAll();
							                $endturn = Item::get(208, 0, 1);
                                            $endturn->setCustomName("§3Zug Beenden");
							                $p->getInventory()->setItem(6, $endturn);
											Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
											return;
										}
										if($gamecfg->get("player4") + $points == 21){
										    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										    $gamecfg->set("freiparken", 0);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									    }
										if($gamecfg->get("player4") + $points == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									    }
										if($gamecfg->get("player4") + $points == 39){
										    EconomyAPI::getInstance()->reduceMoney($p, 2000);
										    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
										    $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									    }
										if($gamecfg->get("player4") + $points == 3 or $gamecfg->get("player4") + $points == 18 or $gamecfg->get("player4") + $points == 34){
											$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
										}
										if($gamecfg->get("player4") + $points == 8 or $gamecfg->get("player4") + $points == 23 or $gamecfg->get("player4") + $points == 37){
											$this->plugin->getEreignis()->EreignisKarte($p);
										}
										if($gamecfg->get($player4->getName()) + $points != null){
										    if($gamecfg->get($player4->getName()) + $points != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
									    $p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
										$xlast = $config->getNested("coords4.knastx");
								        $zlast = $config->getNested("coords4.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									    $gamecfg->set("player4", $gamecfg->get("player4") + $points);
									    $gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
									    $gamecfg->save();
									}else{
										if((($gamecfg->get("player4") + $points) - 40) == 5){
										    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									        $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									        $gamecfg->save();
										    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
								    	}
										if((($gamecfg->get("player4") + $points) - 40) == 3){
										    $this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									    }
									    if((($gamecfg->get("player4") + $points) - 40) == 8){
										    $this->plugin->getEreignis()->EreignisKarte($p);
									    }
										if($gamecfg->get($player4->getName()) + $points - 40 != null){
										    if($gamecfg->get($player4->getName()) + $points - 40 != $p->getName()){
										        $gamecfg->set("miete", true);
									            $gamecfg->save();
										    }
									    }
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."x");
			                            $z = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
										$gamecfg->set("player4", ($gamecfg->get("player4") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}
						}else{
							$gamecfg->set("wurf", true);
							$gamecfg->set("pasch", 0);
							$gamecfg->save();
							if($p->getName() == $Player1){
							    $gamecfg->set("knast1", true);
								$gamecfg->save();
								$x = $config->getNested("coords1.knastx");
								$z = $config->getNested("coords1.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
								$p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}elseif($p->getName() == $Player2){
							    $gamecfg->set("knast2", true);
								$gamecfg->save();
								$x = $config->getNested("coords2.knastx");
								$z = $config->getNested("coords2.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
								$p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}elseif($p->getName() == $Player3){
							    $gamecfg->set("knast3", true);
								$gamecfg->save();
								$x = $config->getNested("coords3.knastx");
								$z = $config->getNested("coords3.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(91, 0));
								$p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}elseif($p->getName() == $Player4){
							    $gamecfg->set("knast4", true);
								$gamecfg->save();
								$x = $config->getNested("coords4.knastx");
								$z = $config->getNested("coords4.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
								$p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}
							$p->getInventory()->clearAll();
							$endturn = Item::get(208, 0, 1);
                            $endturn->setCustomName("§3Zug Beenden");
							$p->getInventory()->setItem(6, $endturn);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat sein 3. Pasch gewürfelt und muss ins Gefängnis!");
						}
				    }else{
						$gamecfg->set("wurf", true);
						$gamecfg->save();
						if($p->getName() == $Player1){
							if($gamecfg->get("knast1") !== false){
								if($gamecfg->get("knast-turn1") < 2){
									$gamecfg->set("knast-turn1", $gamecfg->get("knast-turn1") + 1);
								    $gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat kein Pasch gewürfelt und muss im Gefängnis bleiben!");
								    return;
								}else{
									if($gamecfg->get($gamecfg->get("player1") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player1") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player1") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player1") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player1") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player1->getName()) + $points != null){
										if($gamecfg->get($player1->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									$xlast = $config->getNested("coords1.knastx");
								    $zlast = $config->getNested("coords1.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
									$gamecfg->set("knast1", false);
									$gamecfg->set("knast-turn1", 0);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat beim 3. versuch auch kein Pasch gewürfelt und musste 1000$ Strafe Zahlen!");
									return;
								}
							}else{
								if($gamecfg->get("player1") + $points <= 40){
									if($gamecfg->get("player1") + $points == 31){
										$x = $config->getNested("coords1.knastx");
										$z = $config->getNested("coords1.knastz");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
								        $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
										$gamecfg->set("player1", 11);
										$gamecfg->set("pasch", 0);
										$gamecfg->set("wurf", true);
										$gamecfg->set("knast1", true);
								        $gamecfg->save();
										$p->getInventory()->clearAll();
							            $endturn = Item::get(208, 0, 1);
                                        $endturn->setCustomName("§3Zug Beenden");
							            $p->getInventory()->setItem(6, $endturn);
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
										return;
									}
									if($gamecfg->get("player1") + $points == 21){
										EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										$gamecfg->set("freiparken", 0);
										$gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player1") + $points == 5){
									    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if($gamecfg->get("player1") + $points == 39){
									    EconomyAPI::getInstance()->reduceMoney($p, 2000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
									    $gamecfg->save();
									    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									}
									if($gamecfg->get("player1") + $points == 3 or $gamecfg->get("player1") + $points == 18 or $gamecfg->get("player1") + $points == 34){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player1") + $points == 8 or $gamecfg->get("player1") + $points == 23 or $gamecfg->get("player1") + $points == 37){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									$p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
									$gamecfg->set("player1", $gamecfg->get("player1") + $points);
									$gamecfg->set("wurf", true);
									$gamecfg->save();
									if($gamecfg->get($player1->getName()) + $points != null){
										if($gamecfg->get($player1->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
								}else{
									if((($gamecfg->get("player1") + $points) - 40) == 5){
										EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if((($gamecfg->get("player1") + $points) - 40) == 3){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if((($gamecfg->get("player1") + $points) - 40) == 8){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player1->getName()) + $points - 40 != null){
										if($gamecfg->get($player1->getName()) + $points - 40 != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."x");
			                        $z = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									$p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(0, 0));
									$gamecfg->set("player1", ($gamecfg->get("player1") + $points) - 40);
									$gamecfg->set("wurf", true);
							 	    $gamecfg->save();
								}
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$points." §aGewürfelt.");
							}
						}elseif($p->getName() == $Player2){
						    if($gamecfg->get("knast2") !== false){
							    if($gamecfg->get("knast-turn2") < 2){
									$gamecfg->set("knast-turn2", $gamecfg->get("knast-turn2") + 1);
								    $gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat kein Pasch gewürfelt und muss im Gefängnis bleiben!");
								    return;
								}else{
									if($gamecfg->get($gamecfg->get("player2") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player2") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player2") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player2") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player2") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player2->getName()) + $points != null){
										if($gamecfg->get($player2->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									$xlast = $config->getNested("coords2.knastx");
								    $zlast = $config->getNested("coords2.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
									$gamecfg->set("knast1", false);
									$gamecfg->set("knast-turn2", 0);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat beim 3. versuch auch kein Pasch gewürfelt und musste 1000$ Strafe Zahlen!");
									return;
								}
							}else{
								if($gamecfg->get("player2") + $points <= 40){
									if($gamecfg->get("player2") + $points == 31){
										$x = $config->getNested("coords2.knastx");
										$z = $config->getNested("coords2.knastz");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
								        $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
										$gamecfg->set("player2", 11);
										$gamecfg->set("pasch", 0);
										$gamecfg->set("wurf", true);
										$gamecfg->set("knast2", true);
								        $gamecfg->save();
										$p->getInventory()->clearAll();
							            $endturn = Item::get(208, 0, 1);
                                        $endturn->setCustomName("§3Zug Beenden");
							            $p->getInventory()->setItem(6, $endturn);
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
										return;
									}
									if($gamecfg->get("player2") + $points == 21){
										EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										$gamecfg->set("freiparken", 0);
										$gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player2") + $points == 5){
									    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if($gamecfg->get("player2") + $points == 39){
									    EconomyAPI::getInstance()->reduceMoney($p, 2000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
									    $gamecfg->save();
									    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									}
									if($gamecfg->get("player2") + $points == 3 or $gamecfg->get("player2") + $points == 18 or $gamecfg->get("player2") + $points == 34){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player2") + $points == 8 or $gamecfg->get("player2") + $points == 23 or $gamecfg->get("player2") + $points == 37){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player2->getName()) + $points != null){
										if($gamecfg->get($player2->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									$p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
									$gamecfg->set("player2", $gamecfg->get("player2") + $points);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
								}else{
									if((($gamecfg->get("player2") + $points) - 40) == 5){
										EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if((($gamecfg->get("player2") + $points) - 40) == 3){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if((($gamecfg->get("player2") + $points) - 40) == 8){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player2->getName()) + $points - 40 != null){
										if($gamecfg->get($player2->getName()) + $points - 40 != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."x");
			                        $z = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
									$p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(0, 0));
									$gamecfg->set("player2", ($gamecfg->get("player2") + $points) - 40);
									$gamecfg->set("wurf", true);
							 	    $gamecfg->save();
								}
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$points." §aGewürfelt.");
							}
						}elseif($p->getName() == $Player3){
						    if($gamecfg->get("knast3") !== false){
							    if($gamecfg->get("knast-turn3") < 2){
									$gamecfg->set("knast-turn3", $gamecfg->get("knast-turn3") + 1);
								    $gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat kein Pasch gewürfelt und muss im Gefängnis bleiben!");
								    return;
								}else{
									if($gamecfg->get($gamecfg->get("player3") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player3") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player3") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player3") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player3") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player3->getName()) + $points != null){
										if($gamecfg->get($player3->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									$xlast = $config->getNested("coords3.knastx");
								    $zlast = $config->getNested("coords3.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
									$gamecfg->set("knast1", false);
									$gamecfg->set("knast-turn3", 0);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat beim 3. versuch auch kein Pasch gewürfelt und musste 1000$ Strafe Zahlen!");
									return;
								}
							}else{
								if($gamecfg->get("player3") + $points <= 40){
									if($gamecfg->get("player3") + $points == 31){
										$x = $config->getNested("coords3.knastx");
										$z = $config->getNested("coords3.knastz");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
								        $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
										$gamecfg->set("player3", 11);
										$gamecfg->set("pasch", 0);
										$gamecfg->set("wurf", true);
										$gamecfg->set("knast3", true);
								        $gamecfg->save();
										$p->getInventory()->clearAll();
							            $endturn = Item::get(208, 0, 1);
                                        $endturn->setCustomName("§3Zug Beenden");
							            $p->getInventory()->setItem(6, $endturn);
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
										return;
									}
									if($gamecfg->get("player3") + $points == 21){
										EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										$gamecfg->set("freiparken", 0);
										$gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player3") + $points == 5){
									    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if($gamecfg->get("player3") + $points == 39){
									    EconomyAPI::getInstance()->reduceMoney($p, 2000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
									    $gamecfg->save();
									    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									}
									if($gamecfg->get("player3") + $points == 3 or $gamecfg->get("player3") + $points == 18 or $gamecfg->get("player3") + $points == 34){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player3") + $points == 8 or $gamecfg->get("player3") + $points == 23 or $gamecfg->get("player3") + $points == 37){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player3->getName()) + $points != null){
										if($gamecfg->get($player3->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									$p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
									$gamecfg->set("player3", $gamecfg->get("player3") + $points);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
								}else{
									if((($gamecfg->get("player3") + $points) - 40) == 5){
										EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if((($gamecfg->get("player3") + $points) - 40) == 3){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if((($gamecfg->get("player3") + $points) - 40) == 8){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player3->getName()) + $points - 40 != null){
										if($gamecfg->get($player3->getName()) + $points - 40 != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."x");
			                        $z = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(91, 0));
									$p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(0, 0));
									$gamecfg->set("player3", ($gamecfg->get("player3") + $points) - 40);
									$gamecfg->set("wurf", true);
							 	    $gamecfg->save();
								}
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$points." §aGewürfelt.");
							}
						}elseif($p->getName() == $Player4){
						    if($gamecfg->get("knast4") !== false){
								if($gamecfg->get("knast-turn4") < 2){
									$gamecfg->set("knast-turn4", $gamecfg->get("knast-turn4") + 1);
								    $gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat kein Pasch gewürfelt und muss im Gefängnis bleiben!");
								    return;
								}else{
									if($gamecfg->get($gamecfg->get("player4") + $points) == null){
										$kaufen = Item::get(266, 0, 1);
                                        $kaufen->setCustomName("§6Kaufen");
										$p->getInventory()->setItem(1, $kaufen);
									}elseif($gamecfg->get($gamecfg->get("player4") + $points) != $p->getName()){
										$pay = Item::get(371, 0, 1);
                                        $pay->setCustomName("§6Miete Bezahlen");
										$p->getInventory()->setItem(1, $pay);
										$gamecfg->set("miete", true);
										$gamecfg->save();
									}
									if($gamecfg->get("player4") + $points == 21){
									    EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
									    $gamecfg->set("freiparken", 0);
									    $gamecfg->save();
							    	    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player4") + $points == 18){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player4") + $points == 23){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player4->getName()) + $points != null){
										if($gamecfg->get($player4->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									$xlast = $config->getNested("coords4.knastx");
								    $zlast = $config->getNested("coords4.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(0, 0));
									$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
									$gamecfg->set("knast1", false);
									$gamecfg->set("knast-turn4", 0);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat beim 3. versuch auch kein Pasch gewürfelt und musste 1000$ Strafe Zahlen!");
									return;
								}
							}else{
								if($gamecfg->get("player4") + $points <= 40){
									if($gamecfg->get("player4") + $points == 31){
										$x = $config->getNested("coords4.knastx");
										$z = $config->getNested("coords4.knastz");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
								        $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
										$gamecfg->set("player4", 11);
										$gamecfg->set("pasch", 0);
										$gamecfg->set("wurf", true);
										$gamecfg->set("knast4", true);
								        $gamecfg->save();
										$p->getInventory()->clearAll();
							            $endturn = Item::get(208, 0, 1);
                                        $endturn->setCustomName("§3Zug Beenden");
							            $p->getInventory()->setItem(6, $endturn);
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt und ist auf das Feld §dGehe in das Gefängnis §agekommen und muss deswegen in das gefängnis.");
										return;
									}
									if($gamecfg->get("player4") + $points == 21){
										EconomyAPI::getInstance()->addMoney($p, $gamecfg->get("freiparken"));
										$gamecfg->set("freiparken", 0);
										$gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Frei Parken gekommen und bekommt §d".$gamecfg->get("freiparken")."§a$.");
									}
									if($gamecfg->get("player4") + $points == 5){
									    EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if($gamecfg->get("player4") + $points == 39){
									    EconomyAPI::getInstance()->reduceMoney($p, 2000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 2000);
									    $gamecfg->save();
									    Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Zusatzsteuer gekommen und muss §d2000§a$ bezahlen.");
									}
									if($gamecfg->get("player4") + $points == 3 or $gamecfg->get("player4") + $points == 18 or $gamecfg->get("player4") + $points == 34){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if($gamecfg->get("player4") + $points == 8 or $gamecfg->get("player4") + $points == 23 or $gamecfg->get("player4") + $points == 37){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player4->getName()) + $points != null){
										if($gamecfg->get($player4->getName()) + $points != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									$p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
									$gamecfg->set("player4", $gamecfg->get("player4") + $points);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
								}else{
									if((($gamecfg->get("player4") + $points) - 40) == 5){
										EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									if((($gamecfg->get("player4") + $points) - 40) == 3){
										$this->plugin->getGemeinschaft()->GemeinschaftsKarte($p);
									}
									if((($gamecfg->get("player4") + $points) - 40) == 8){
										$this->plugin->getEreignis()->EreignisKarte($p);
									}
									if($gamecfg->get($player4->getName()) + $points - 40 != null){
										if($gamecfg->get($player4->getName()) + $points - 40 != $p->getName()){
										    $gamecfg->set("miete", true);
									        $gamecfg->save();
										}
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf/über Los gekommen und zieht §d4000§a$ ein.");
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."x");
			                        $z = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
									$p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(0, 0));
									$gamecfg->set("player4", ($gamecfg->get("player4") + $points) - 40);
									$gamecfg->set("wurf", true);
							 	    $gamecfg->save();
								}
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$points." §aGewürfelt.");
							}
						}
					}
				}else{
					$p->sendMessage("§bMono§6poly: §cDu hast schon gewürfelt.");
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}