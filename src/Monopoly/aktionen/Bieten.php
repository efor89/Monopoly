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

class Bieten implements Listener{

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
		if($gamecfg->get("turn") == $Player1){
    		$feld = $gamecfg->get("player1");
		}elseif($gamecfg->get("turn") == $Player2){
		    $feld = $gamecfg->get("player2");
		}elseif($gamecfg->get("turn") == $Player3){
		    $feld = $gamecfg->get("player3");
		}elseif($gamecfg->get("turn") == $Player4){
		    $feld = $gamecfg->get("player4");
		}
		if($Player1 == $gamecfg->get("turn")){
			$strasse = $config->getNested($feld.".name");
		}elseif($Player2 == $gamecfg->get("turn")){
			$strasse = $config->getNested($feld.".name");
		}elseif($Player3 == $gamecfg->get("turn")){
			$strasse = $config->getNested($feld.".name");
		}elseif($Player4 == $gamecfg->get("turn")){
			$strasse = $config->getNested($feld.".name");
		}
		$playerMoney = EconomyAPI::getInstance()->myMoney($p);
		if($item->getId() === 1) {
            if($item->getName() === "§6Biete 1$") {
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
				if($playerMoney >= $gamecfg->get("gebot") + 1){
                    $gamecfg->set("gebot", $gamecfg->get("gebot") + 1);
					$gamecfg->save();
					$p->getInventory()->clearAll();
					$p->getInventory()->setItem(7, $exit);
                    $p->getInventory()->setItem(8, $giveup);
					if($p->getName() == $Player1 and $Player2 != null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
						    $player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player1 and $Player2 == null and $Player3 != null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player1 and $Player2 == null and $Player3 == null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player2 and $Player3 != null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
						    $player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player2 and $Player3 == null and $Player4 != null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player2 and $Player3 == null and $Player4 == null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player3 and $Player4 != null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
						    $player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player3 and $Player4 == null and $Player1 != null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player3 and $Player4 == null and $Player1 == null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player4 and $Player1 != null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
						    $player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player4 and $Player1 == null and $Player2 != null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player4 and $Player1 == null and $Player2 == null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
				}else{
					if($p->getName() == $Player1){
					    $gamecfg->set("bieter1", false);
					    $gamecfg->save();
						if($Player2 != null and $Player3 != null and $Player4 != null){
							if($gamecfg->get("bieter2") == true){
								$player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(0, $b1);
						        $player2->getInventory()->setItem(1, $b100);
						        $player2->getInventory()->setItem(2, $b1000);
							    $player2->getInventory()->setItem(7, $exit);
                                $player2->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter4", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player2 == null and $Player3 != null and $Player4 != null){
							if($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter4", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player2 == null and $Player3 == null and $Player4 != null){
							EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player4->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player2){
					    $gamecfg->set("bieter2", false);
					    $gamecfg->save();
						if($Player3 != null and $Player4 != null and $Player1 != null){
							if($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter1", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player3 == null and $Player4 != null and $Player1 != null){
							if($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter1", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player3 == null and $Player4 == null and $Player1 != null){
							EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player1->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player3){
					    $gamecfg->set("bieter3", false);
					    $gamecfg->save();
						if($Player4 != null and $Player1 != null and $Player2 != null){
							if($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter2", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 != null and $Player2 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter2", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 == null and $Player2 != null){
							EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player2->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player4){
					    $gamecfg->set("bieter4", false);
					    $gamecfg->save();
						if($Player1 != null and $Player2 != null and $Player3 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter2") == true){
								$player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(0, $b1);
						        $player2->getInventory()->setItem(1, $b100);
						        $player2->getInventory()->setItem(2, $b1000);
							    $player2->getInventory()->setItem(7, $exit);
                                $player2->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter3", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player1 == null and $Player2 != null and $Player3 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter3", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 == null and $Player2 != null){
							EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player3->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
				}
            }
        }
		if($item->getId() === 266) {
            if($item->getName() === "§aBiete 100$") {
            if($p->getName() == $Player1){
				    $feld = $gamecfg->get("player1");
				}elseif($p->getName() == $Player2){
				    $feld = $gamecfg->get("player2");
				}elseif($p->getName() == $Player3){
				    $feld = $gamecfg->get("player3");
				}elseif($p->getName() == $Player4){
				    $feld = $gamecfg->get("player4");
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
				if($playerMoney >= $gamecfg->get("gebot") + 100){
                    $gamecfg->set("gebot", $gamecfg->get("gebot") + 100);
					$gamecfg->save();
					$p->getInventory()->clearAll();
					$p->getInventory()->setItem(7, $exit);
                    $p->getInventory()->setItem(8, $giveup);
					if($p->getName() == $Player1 and $Player2 != null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
						    $player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player1 and $Player2 == null and $Player3 != null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player1 and $Player2 == null and $Player3 == null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player2 and $Player3 != null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
						    $player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player2 and $Player3 == null and $Player4 != null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player2 and $Player3 == null and $Player4 == null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player3 and $Player4 != null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
						    $player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player3 and $Player4 == null and $Player1 != null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player3 and $Player4 == null and $Player1 == null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player4 and $Player1 != null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
						    $player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player4 and $Player1 == null and $Player2 != null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player4 and $Player1 == null and $Player2 == null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
				}else{
					if($p->getName() == $Player1){
					    $gamecfg->set("bieter1", false);
					    $gamecfg->save();
						if($Player2 != null and $Player3 != null and $Player4 != null){
							if($gamecfg->get("bieter2") == true){
								$player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(0, $b1);
						        $player2->getInventory()->setItem(1, $b100);
						        $player2->getInventory()->setItem(2, $b1000);
							    $player2->getInventory()->setItem(7, $exit);
                                $player2->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter4", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player2 == null and $Player3 != null and $Player4 != null){
							if($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter4", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player2 == null and $Player3 == null and $Player4 != null){
							EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player4->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player2){
					    $gamecfg->set("bieter2", false);
					    $gamecfg->save();
						if($Player3 != null and $Player4 != null and $Player1 != null){
							if($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter1", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player3 == null and $Player4 != null and $Player1 != null){
							if($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter1", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player3 == null and $Player4 == null and $Player1 != null){
							EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player1->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player3){
					    $gamecfg->set("bieter3", false);
					    $gamecfg->save();
						if($Player4 != null and $Player1 != null and $Player2 != null){
							if($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter2", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 != null and $Player2 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter2", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 == null and $Player2 != null){
							EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player2->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player4){
					    $gamecfg->set("bieter4", false);
					    $gamecfg->save();
						if($Player1 != null and $Player2 != null and $Player3 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter2") == true){
								$player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(0, $b1);
						        $player2->getInventory()->setItem(1, $b100);
						        $player2->getInventory()->setItem(2, $b1000);
							    $player2->getInventory()->setItem(7, $exit);
                                $player2->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter3", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player1 == null and $Player2 != null and $Player3 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter3", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 == null and $Player2 != null){
							EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player3->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
				}
            }
        }
		if($item->getId() === 264) {
            if($item->getName() === "§bBiete 1000$") {
                if($p->getName() == $Player1){
				    $feld = $gamecfg->get("player1");
				}elseif($p->getName() == $Player2){
				    $feld = $gamecfg->get("player2");
				}elseif($p->getName() == $Player3){
				    $feld = $gamecfg->get("player3");
				}elseif($p->getName() == $Player4){
				    $feld = $gamecfg->get("player4");
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
				if($playerMoney >= $gamecfg->get("gebot") + 1000){
                    $gamecfg->set("gebot", $gamecfg->get("gebot") + 1000);
					$gamecfg->save();
					$p->getInventory()->clearAll();
					$p->getInventory()->setItem(7, $exit);
                    $p->getInventory()->setItem(8, $giveup);
					if($p->getName() == $Player1 and $Player2 != null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
						    $player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player1 and $Player2 == null and $Player3 != null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player1 and $Player2 == null and $Player3 == null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player2 and $Player3 != null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
						    $player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player2 and $Player3 == null and $Player4 != null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
							$player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player2 and $Player3 == null and $Player4 == null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player3 and $Player4 != null){
						if($gamecfg->get("bieter4") == true){
							$player4->getInventory()->clearAll();
						    $player4->getInventory()->setItem(0, $b1);
						    $player4->getInventory()->setItem(1, $b100);
						    $player4->getInventory()->setItem(2, $b1000);
							$player4->getInventory()->setItem(7, $exit);
                            $player4->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player3 and $Player4 == null and $Player1 != null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
							$player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player3 and $Player4 == null and $Player1 == null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
					
					if($p->getName() == $Player4 and $Player1 != null){
						if($gamecfg->get("bieter1") == true){
							$player1->getInventory()->clearAll();
						    $player1->getInventory()->setItem(0, $b1);
						    $player1->getInventory()->setItem(1, $b100);
						    $player1->getInventory()->setItem(2, $b1000);
							$player1->getInventory()->setItem(7, $exit);
                            $player1->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->clearAll();
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
					            $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}elseif($p->getName() == $Player4 and $Player1 == null and $Player2 != null){
						if($gamecfg->get("bieter2") == true){
							$player2->getInventory()->clearAll();
							$player2->getInventory()->setItem(0, $b1);
						    $player2->getInventory()->setItem(1, $b100);
						    $player2->getInventory()->setItem(2, $b1000);
							$player2->getInventory()->setItem(7, $exit);
                            $player2->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}elseif($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->clearAll();
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
					            $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
				    }elseif($p->getName() == $Player4 and $Player1 == null and $Player2 == null){
						if($gamecfg->get("bieter3") == true){
							$player3->getInventory()->clearAll();
							$player3->getInventory()->setItem(0, $b1);
						    $player3->getInventory()->setItem(1, $b100);
						    $player3->getInventory()->setItem(2, $b1000);
							$player3->getInventory()->setItem(7, $exit);
                            $player3->getInventory()->setItem(8, $giveup);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aHat ein Gebot von §d".$gamecfg->get("gebot")."§a$ abgegeben.");
						}else{
							EconomyAPI::getInstance()->reduceMoney($p, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $p->getName());
							$gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
							$gamecfg->save();
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->clearAll();
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
					            $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}elseif($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->clearAll();
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
					            $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
						}
					}
				}else{
					if($p->getName() == $Player1){
					    $gamecfg->set("bieter1", false);
					    $gamecfg->save();
						if($Player2 != null and $Player3 != null and $Player4 != null){
							if($gamecfg->get("bieter2") == true){
								$player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(0, $b1);
						        $player2->getInventory()->setItem(1, $b100);
						        $player2->getInventory()->setItem(2, $b1000);
							    $player2->getInventory()->setItem(7, $exit);
                                $player2->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter4", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player2 == null and $Player3 != null and $Player4 != null){
							if($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player4->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter4", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player2 == null and $Player3 == null and $Player4 != null){
							EconomyAPI::getInstance()->reduceMoney($player4, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player4->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter4", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player2){
					    $gamecfg->set("bieter2", false);
					    $gamecfg->save();
						if($Player3 != null and $Player4 != null and $Player1 != null){
							if($gamecfg->get("bieter3") == true){
								$player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(0, $b1);
						        $player3->getInventory()->setItem(1, $b100);
						        $player3->getInventory()->setItem(2, $b1000);
							    $player3->getInventory()->setItem(7, $exit);
                                $player3->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter1", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player3 == null and $Player4 != null and $Player1 != null){
							if($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player1->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter1", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player3 == null and $Player4 == null and $Player1 != null){
							EconomyAPI::getInstance()->reduceMoney($player1, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player1->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter1", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player3){
					    $gamecfg->set("bieter3", false);
					    $gamecfg->save();
						if($Player4 != null and $Player1 != null and $Player2 != null){
							if($gamecfg->get("bieter4") == true){
								$player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(0, $b1);
						        $player4->getInventory()->setItem(1, $b100);
						        $player4->getInventory()->setItem(2, $b1000);
							    $player4->getInventory()->setItem(7, $exit);
                                $player4->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter2", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 != null and $Player2 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player2->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter2", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 == null and $Player2 != null){
							EconomyAPI::getInstance()->reduceMoney($player2, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player2->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter2", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
					if($p->getName() == $Player4){
					    $gamecfg->set("bieter4", false);
					    $gamecfg->save();
						if($Player1 != null and $Player2 != null and $Player3 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}elseif($gamecfg->get("bieter2") == true){
								$player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(0, $b1);
						        $player2->getInventory()->setItem(1, $b100);
						        $player2->getInventory()->setItem(2, $b1000);
							    $player2->getInventory()->setItem(7, $exit);
                                $player2->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter3", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player1 == null and $Player2 != null and $Player3 != null){
							if($gamecfg->get("bieter1") == true){
								$player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(0, $b1);
						        $player1->getInventory()->setItem(1, $b100);
						        $player1->getInventory()->setItem(2, $b1000);
							    $player1->getInventory()->setItem(7, $exit);
                                $player1->getInventory()->setItem(8, $giveup);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §chatte nicht genug Geld um ein Gebot abzugeben.");
							}else{
								EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							    $gamecfg->set($feld, $player3->getName());
							    $gamecfg->set("gebot", 0);
								$gamecfg->set("bieter3", false);
					            $gamecfg->save();
								if($Player1 != null){
								    $player1->getInventory()->clearAll();
								    $player1->getInventory()->setItem(7, $info);
                                    $player1->getInventory()->setItem(8, $giveup);
								}
								if($Player2 != null){
								    $player2->getInventory()->clearAll();
								    $player2->getInventory()->setItem(7, $info);
                                    $player2->getInventory()->setItem(8, $giveup);
								}
								if($Player3 != null){
								    $player3->getInventory()->clearAll();
								    $player3->getInventory()->setItem(7, $info);
                                    $player3->getInventory()->setItem(8, $giveup);
								}
								if($Player4 != null){
								    $player4->getInventory()->clearAll();
								    $player4->getInventory()->setItem(7, $info);
                                    $player4->getInventory()->setItem(8, $giveup);
								}
								if($gamecfg->get("turn") == $Player1){
									$player1->getInventory()->setItem(0, $wuerfeln);
                                    $player1->getInventory()->setItem(1, $kaufen);
                                    $player1->getInventory()->setItem(2, $bauen);
                                    $player1->getInventory()->setItem(3, $hypo);
                                    $player1->getInventory()->setItem(4, $handeln);
					                $player1->getInventory()->setItem(5, $pay);
                                    $player1->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player2){
									$player2->getInventory()->setItem(0, $wuerfeln);
                                    $player2->getInventory()->setItem(1, $kaufen);
                                    $player2->getInventory()->setItem(2, $bauen);
                                    $player2->getInventory()->setItem(3, $hypo);
                                    $player2->getInventory()->setItem(4, $handeln);
					                $player2->getInventory()->setItem(5, $pay);
                                    $player2->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player3){
									$player3->getInventory()->setItem(0, $wuerfeln);
                                    $player3->getInventory()->setItem(1, $kaufen);
                                    $player3->getInventory()->setItem(2, $bauen);
                                    $player3->getInventory()->setItem(3, $hypo);
                                    $player3->getInventory()->setItem(4, $handeln);
					                $player3->getInventory()->setItem(5, $pay);
                                    $player3->getInventory()->setItem(6, $endturn);
								}
								if($gamecfg->get("turn") == $Player4){
									$player4->getInventory()->setItem(0, $wuerfeln);
                                    $player4->getInventory()->setItem(1, $kaufen);
                                    $player4->getInventory()->setItem(2, $bauen);
                                    $player4->getInventory()->setItem(3, $hypo);
                                    $player4->getInventory()->setItem(4, $handeln);
					                $player4->getInventory()->setItem(5, $pay);
                                    $player4->getInventory()->setItem(6, $endturn);
								}
							}
						}elseif($Player4 == null and $Player1 == null and $Player2 != null){
							EconomyAPI::getInstance()->reduceMoney($player3, $gamecfg->get("gebot"));
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat mit dem Gebot von §d".$gamecfg->get("gebot")."§a$ die Strasse §d".$strasse."§a gewonnen.");
							$gamecfg->set($feld, $player3->getName());
						    $gamecfg->set("gebot", 0);
							$gamecfg->set("bieter3", false);
				            $gamecfg->save();
							if($Player1 != null){
							    $player1->getInventory()->clearAll();
							    $player1->getInventory()->setItem(7, $info);
                                $player1->getInventory()->setItem(8, $giveup);
							}
							if($Player2 != null){
							    $player2->getInventory()->clearAll();
							    $player2->getInventory()->setItem(7, $info);
                                $player2->getInventory()->setItem(8, $giveup);
							}
							if($Player3 != null){
							    $player3->getInventory()->clearAll();
							    $player3->getInventory()->setItem(7, $info);
                                $player3->getInventory()->setItem(8, $giveup);
							}
							if($Player4 != null){
							    $player4->getInventory()->clearAll();
							    $player4->getInventory()->setItem(7, $info);
                                $player4->getInventory()->setItem(8, $giveup);
							}
							if($gamecfg->get("turn") == $Player1){
								$player1->getInventory()->setItem(0, $wuerfeln);
                                $player1->getInventory()->setItem(1, $kaufen);
                                $player1->getInventory()->setItem(2, $bauen);
                                $player1->getInventory()->setItem(3, $hypo);
                                $player1->getInventory()->setItem(4, $handeln);
					            $player1->getInventory()->setItem(5, $pay);
                                $player1->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player2){
								$player2->getInventory()->setItem(0, $wuerfeln);
                                $player2->getInventory()->setItem(1, $kaufen);
                                $player2->getInventory()->setItem(2, $bauen);
                                $player2->getInventory()->setItem(3, $hypo);
                                $player2->getInventory()->setItem(4, $handeln);
					            $player2->getInventory()->setItem(5, $pay);
                                $player2->getInventory()->setItem(6, $endturn);
							}
							if($gamecfg->get("turn") == $Player3){
								$player3->getInventory()->setItem(0, $wuerfeln);
                                $player3->getInventory()->setItem(1, $kaufen);
                                $player3->getInventory()->setItem(2, $bauen);
                                $player3->getInventory()->setItem(3, $hypo);
                                $player3->getInventory()->setItem(4, $handeln);
					            $player3->getInventory()->setItem(5, $pay);
                                $player3->getInventory()->setItem(6, $endturn);
				    		}
							if($gamecfg->get("turn") == $Player4){
								$player4->getInventory()->setItem(0, $wuerfeln);
                                $player4->getInventory()->setItem(1, $kaufen);
                                $player4->getInventory()->setItem(2, $bauen);
                                $player4->getInventory()->setItem(3, $hypo);
                                $player4->getInventory()->setItem(4, $handeln);
					            $player4->getInventory()->setItem(5, $pay);
                                $player4->getInventory()->setItem(6, $endturn);
							}
						}
					}
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}