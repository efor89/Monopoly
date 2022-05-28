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

class NichtBieten implements Listener{

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
		if($item->getId() === 331) {
            if($item->getName() === "§cNicht Bieten") {
                if($p->getName() == $Player1){
				    $feld = $gamecfg->get("player1");
				}elseif($p->getName() == $Player2){
				    $feld = $gamecfg->get("player2");
				}elseif($p->getName() == $Player3){
				    $feld = $gamecfg->get("player3");
				}elseif($p->getName() == $Player4){
				    $feld = $gamecfg->get("player4");
				}
				if($Player1 == $gamecfg->get("turn")){
					$strasse = $config->getNested($gamecfg->get("player1").".name");
				}elseif($Player2 == $gamecfg->get("turn")){
					$strasse = $config->getNested($gamecfg->get("player2").".name");
				}elseif($Player3 == $gamecfg->get("turn")){
					$strasse = $config->getNested($gamecfg->get("player3").".name");
				}elseif($Player4 == $gamecfg->get("turn")){
					$strasse = $config->getNested($gamecfg->get("player4").".name");
				}
				$b1 = Item::get(1, 0, 1);
                $b1->setCustomName("§6Biete 1$");
				$b100 = Item::get(266, 0, 1);
                $b100->setCustomName("§aBiete 100$");
				$b1000 = Item::get(264, 0, 1);
                $b1000->setCustomName("§bBiete 1000$");
				$exit = Item::get(331, 14, 1);
                $exit->setCustomName("§cNicht Bieten");
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
				if($gamecfg->get("gebot") != null){
					$p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
					return;
				}
                if($p->getName() == $Player1){
					if($p->getName == $gamecfg->get("turn")){
						if($gamecfg->get("bieter2") == true and $gamecfg->get("bieter3") == true and $gamecfg->get("bieter4") == true){
							$player2->getInventory()->clearAll();
						    $player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter2") != true and $gamecfg->get("bieter3") == true and $gamecfg->get("bieter4") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter2") == true and $gamecfg->get("bieter3") != true and $gamecfg->get("bieter4") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter2") == true and $gamecfg->get("bieter3") == true and $gamecfg->get("bieter4") != true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}else{
							if($gamecfg->get("bieter2") == true){
							    EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter2", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter3") == true){
							    EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter3", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter4") == true){
							    EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter4", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}
						}
					}else{
						if($p->getName() == $Player1){
						    if($gamecfg->get("bieter1") == true){
					            $gamecfg->set("bieter1", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player2){
					        if($gamecfg->get("bieter2") == true){
					            $gamecfg->set("bieter2", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player3){
					        if($gamecfg->get("bieter3") == true){
					            $gamecfg->set("bieter3", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player4){
					        if($gamecfg->get("bieter4") == true){
					            $gamecfg->set("bieter4", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }
					}
					$gamecfg->set("bieter1", false);
					$gamecfg->save();
				}elseif($p->getName() == $Player2){
					if($p->getName == $gamecfg->get("turn")){
						if($gamecfg->get("bieter3") == true and $gamecfg->get("bieter3") == true and $gamecfg->get("bieter1") == true){
							$player3->getInventory()->clearAll();
						    $player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter3") != true and $gamecfg->get("bieter4") == true and $gamecfg->get("bieter1") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter3") == true and $gamecfg->get("bieter4") != true and $gamecfg->get("bieter1") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter3") == true and $gamecfg->get("bieter4") == true and $gamecfg->get("bieter1") != true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}else{
							if($gamecfg->get("bieter1") == true){
							    EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter1", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter3") == true){
							    EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter3", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter4") == true){
							    EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter4", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}
						}
					}else{
						if($p->getName() == $Player1){
						    if($gamecfg->get("bieter1") == true){
					            $gamecfg->set("bieter1", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player2){
					        if($gamecfg->get("bieter2") == true){
					            $gamecfg->set("bieter2", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player3){
					        if($gamecfg->get("bieter3") == true){
					            $gamecfg->set("bieter3", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player4){
					        if($gamecfg->get("bieter4") == true){
					            $gamecfg->set("bieter4", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }
					}
					$gamecfg->set("bieter2", false);
					$gamecfg->save();
				}elseif($p->getName() == $Player3){
					if($p->getName == $gamecfg->get("turn")){
						if($gamecfg->get("bieter4") == true and $gamecfg->get("bieter1") == true and $gamecfg->get("bieter2") == true){
							$player4->getInventory()->clearAll();
						    $player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter4") != true and $gamecfg->get("bieter1") == true and $gamecfg->get("bieter2") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter4") == true and $gamecfg->get("bieter1") != true and $gamecfg->get("bieter2") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter4") == true and $gamecfg->get("bieter1") == true and $gamecfg->get("bieter2") != true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}else{
							if($gamecfg->get("bieter1") == true){
							    EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter1", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter2") == true){
							    EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter2", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter4") == true){
							    EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter4", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}
						}
					}else{
						if($p->getName() == $Player1){
						    if($gamecfg->get("bieter1") == true){
					            $gamecfg->set("bieter1", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player2){
					        if($gamecfg->get("bieter2") == true){
					            $gamecfg->set("bieter2", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player3){
					        if($gamecfg->get("bieter3") == true){
					            $gamecfg->set("bieter3", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player4){
					        if($gamecfg->get("bieter4") == true){
					            $gamecfg->set("bieter4", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }
					}
					$gamecfg->set("bieter3", false);
					$gamecfg->save();
				}elseif($p->getName() == $Player4){
					if($p->getName == $gamecfg->get("turn")){
						if($gamecfg->get("bieter1") == true and $gamecfg->get("bieter2") == true and $gamecfg->get("bieter3") == true){
							$player1->getInventory()->clearAll();
						    $player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter1") != true and $gamecfg->get("bieter2") == true and $gamecfg->get("bieter3") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter1") == true and $gamecfg->get("bieter2") != true and $gamecfg->get("bieter3") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}elseif($gamecfg->get("bieter1") == true and $gamecfg->get("bieter2") == true and $gamecfg->get("bieter3") != true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
						}else{
							if($gamecfg->get("bieter1") == true){
							    EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter1", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter2") == true){
							    EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter2", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}elseif($gamecfg->get("bieter3") == true){
							    EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
							    $gamecfg->set("bieter3", false);
							    $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}
						}
					}else{
						if($p->getName() == $Player1){
						    if($gamecfg->get("bieter1") == true){
					            $gamecfg->set("bieter1", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player2){
					        if($gamecfg->get("bieter2") == true){
					            $gamecfg->set("bieter2", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player3){
					        if($gamecfg->get("bieter3") == true){
					            $gamecfg->set("bieter3", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }elseif($p->getName() == $Player4){
					        if($gamecfg->get("bieter4") == true){
					            $gamecfg->set("bieter4", false);
					            $gamecfg->save();
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §cBietet nicht mehr mit.");
							}else{
								$p->sendMessage("§bMono§6poly: §cDu kannst nicht mehr mit bieten.");
							}
					    }
					}
					$gamecfg->set("bieter4", false);
					$gamecfg->save();
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}