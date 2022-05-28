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

class ZugBeenden implements Listener{

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
		
		if($item->getId() === 208) {
            if($item->getName() === "§3Zug Beenden") {
			    if($gamecfg->get("wurf") !== true){
			        $p->sendMessage("§bMono§6poly: §cDu musst noch würfeln bevor du deinen Zug beenden kannst!");
			        return;
		        }
		        if($gamecfg->get("miete") == true){
			        $p->sendMessage("§bMono§6poly: §cDu musst noch Miete Bezahlen bevor du dein Zug beenden kannst!");
			        return;
		        }
                $p->getInventory()->clearAll();
				$wuerfeln = Item::get(236, 0, 1);
                $wuerfeln->setCustomName("§aWürfeln");
                $kaufen = Item::get(266, 0, 1);
                $kaufen->setCustomName("§6Kaufen");
				$freikaufen = Item::get(264, 0, 1);
                $freikaufen->setCustomName("§6Frei Kaufen");
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
				if($p->getName() === $Player1){
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
		            if($Player2 != null){
						$gamecfg->set("turn", $Player2);
						$gamecfg->save();
						if($gamecfg->get("knast2") !== true){
					        $player2->getInventory()->setItem(0, $wuerfeln);
                            $player2->getInventory()->setItem(1, $kaufen);
                            $player2->getInventory()->setItem(2, $bauen);
                            $player2->getInventory()->setItem(3, $hypo);
                            $player2->getInventory()->setItem(4, $handeln);
							$player2->getInventory()->setItem(5, $pay);
                            $player2->getInventory()->setItem(6, $endturn);
					        $player2->getInventory()->setItem(7, $info);
                            $player2->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player2->getInventory()->setItem(0, $wuerfeln);
						    $player2->getInventory()->setItem(2, $freikaufen);
						    $player2->getInventory()->setItem(6, $endturn);
                            $player2->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player2 == null and $Player3 != null){
			            $gamecfg->set("turn", $Player3);
						$gamecfg->save();
						if($gamecfg->get("knast3") !== true){
					        $player3->getInventory()->setItem(0, $wuerfeln);
                            $player3->getInventory()->setItem(1, $kaufen);
                            $player3->getInventory()->setItem(2, $bauen);
                            $player3->getInventory()->setItem(3, $hypo);
                            $player3->getInventory()->setItem(4, $handeln);
							$player3->getInventory()->setItem(5, $pay);
                            $player3->getInventory()->setItem(6, $endturn);
					        $player3->getInventory()->setItem(7, $info);
                            $player3->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player3->getInventory()->setItem(0, $wuerfeln);
						    $player3->getInventory()->setItem(2, $freikaufen);
						    $player3->getInventory()->setItem(6, $endturn);
                            $player3->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player2 == null and $Player3 == null){
			            $gamecfg->set("turn", $Player4);
						$gamecfg->save();
						if($gamecfg->get("knast4") !== true){
					        $player4->getInventory()->setItem(0, $wuerfeln);
                            $player4->getInventory()->setItem(1, $kaufen);
                            $player4->getInventory()->setItem(2, $bauen);
                            $player4->getInventory()->setItem(3, $hypo);
                            $player4->getInventory()->setItem(4, $handeln);
							$player4->getInventory()->setItem(5, $pay);
                            $player4->getInventory()->setItem(6, $endturn);
					        $player4->getInventory()->setItem(7, $info);
                            $player4->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player4->getInventory()->setItem(0, $wuerfeln);
						    $player4->getInventory()->setItem(2, $freikaufen);
						    $player4->getInventory()->setItem(6, $endturn);
                            $player4->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}
		        }elseif($p->getName() === $Player2){
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();	
					if($Player3 != null){
						$gamecfg->set("turn", $Player3);
						$gamecfg->save();
						if($gamecfg->get("knast3") !== true){
					        $player3->getInventory()->setItem(0, $wuerfeln);
                            $player3->getInventory()->setItem(1, $kaufen);
                            $player3->getInventory()->setItem(2, $bauen);
                            $player3->getInventory()->setItem(3, $hypo);
                            $player3->getInventory()->setItem(4, $handeln);
							$player3->getInventory()->setItem(5, $pay);
                            $player3->getInventory()->setItem(6, $endturn);
					        $player3->getInventory()->setItem(7, $info);
                            $player3->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player3->getInventory()->setItem(0, $wuerfeln);
						    $player3->getInventory()->setItem(2, $freikaufen);
						    $player3->getInventory()->setItem(6, $endturn);
                            $player3->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player3 == null and $Player4 != null){
			            $gamecfg->set("turn", $Player4);
						$gamecfg->save();
						if($gamecfg->get("knast4") !== true){
					        $player4->getInventory()->setItem(0, $wuerfeln);
                            $player4->getInventory()->setItem(1, $kaufen);
                            $player4->getInventory()->setItem(2, $bauen);
                            $player4->getInventory()->setItem(3, $hypo);
                            $player4->getInventory()->setItem(4, $handeln);
							$player4->getInventory()->setItem(5, $pay);
                            $player4->getInventory()->setItem(6, $endturn);
					        $player4->getInventory()->setItem(7, $info);
                            $player4->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player4->getInventory()->setItem(0, $wuerfeln);
						    $player4->getInventory()->setItem(2, $freikaufen);
						    $player4->getInventory()->setItem(6, $endturn);
                            $player4->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player3 == null and $Player4 == null){
			            $gamecfg->set("turn", $Player1);
						$gamecfg->save();
						if($gamecfg->get("knast1") !== true){
					        $player1->getInventory()->setItem(0, $wuerfeln);
                            $player1->getInventory()->setItem(1, $kaufen);
                            $player1->getInventory()->setItem(2, $bauen);
                            $player1->getInventory()->setItem(3, $hypo);
                            $player1->getInventory()->setItem(4, $handeln);
							$player1->getInventory()->setItem(5, $pay);
                            $player1->getInventory()->setItem(6, $endturn);
					        $player1->getInventory()->setItem(7, $info);
                            $player1->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player1->getInventory()->setItem(0, $wuerfeln);
						    $player1->getInventory()->setItem(2, $freikaufen);
						    $player1->getInventory()->setItem(6, $endturn);
                            $player1->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}						
		        }elseif($p->getName() === $Player3){
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
					if($Player4 != null){
						$gamecfg->set("turn", $Player4);
						$gamecfg->save();
						if($gamecfg->get("knast4") !== true){
					        $player4->getInventory()->setItem(0, $wuerfeln);
                            $player4->getInventory()->setItem(1, $kaufen);
                            $player4->getInventory()->setItem(2, $bauen);
                            $player4->getInventory()->setItem(3, $hypo);
                            $player4->getInventory()->setItem(4, $handeln);
							$player4->getInventory()->setItem(5, $pay);
                            $player4->getInventory()->setItem(6, $endturn);
					        $player4->getInventory()->setItem(7, $info);
                            $player4->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player4->getInventory()->setItem(0, $wuerfeln);
						    $player4->getInventory()->setItem(2, $freikaufen);
						    $player4->getInventory()->setItem(6, $endturn);
                            $player4->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player4 == null and $Player1 != null){
			            $gamecfg->set("turn", $Player1);
						$gamecfg->save();
						if($gamecfg->get("knast1") !== true){
					        $player1->getInventory()->setItem(0, $wuerfeln);
                            $player1->getInventory()->setItem(1, $kaufen);
                            $player1->getInventory()->setItem(2, $bauen);
                            $player1->getInventory()->setItem(3, $hypo);
                            $player1->getInventory()->setItem(4, $handeln);
							$player1->getInventory()->setItem(5, $pay);
                            $player1->getInventory()->setItem(6, $endturn);
					        $player1->getInventory()->setItem(7, $info);
                            $player1->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player1->getInventory()->setItem(0, $wuerfeln);
						    $player1->getInventory()->setItem(2, $freikaufen);
						    $player1->getInventory()->setItem(6, $endturn);
                            $player1->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player4 == null and $Player1 == null){
			            $gamecfg->set("turn", $Player2);
						$gamecfg->save();
						if($gamecfg->get("knast2") !== true){
					        $player2->getInventory()->setItem(0, $wuerfeln);
                            $player2->getInventory()->setItem(1, $kaufen);
                            $player2->getInventory()->setItem(2, $bauen);
                            $player2->getInventory()->setItem(3, $hypo);
                            $player2->getInventory()->setItem(4, $handeln);
							$player2->getInventory()->setItem(5, $pay);
                            $player2->getInventory()->setItem(6, $endturn);
					        $player2->getInventory()->setItem(7, $info);
                            $player2->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player2->getInventory()->setItem(0, $wuerfeln);
						    $player2->getInventory()->setItem(2, $freikaufen);
						    $player2->getInventory()->setItem(6, $endturn);
                            $player2->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}
		        }elseif($p->getName() === $Player4){
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
					if($Player1 != null){
						$gamecfg->set("turn", $Player1);
						$gamecfg->save();
						if($gamecfg->get("knast1") !== true){
					        $player1->getInventory()->setItem(0, $wuerfeln);
                            $player1->getInventory()->setItem(1, $kaufen);
                            $player1->getInventory()->setItem(2, $bauen);
                            $player1->getInventory()->setItem(3, $hypo);
                            $player1->getInventory()->setItem(4, $handeln);
							$player1->getInventory()->setItem(5, $pay);
                            $player1->getInventory()->setItem(6, $endturn);
					        $player1->getInventory()->setItem(7, $info);
                            $player1->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player1->getInventory()->setItem(0, $wuerfeln);
						    $player1->getInventory()->setItem(2, $freikaufen);
						    $player1->getInventory()->setItem(6, $endturn);
                            $player1->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player1 == null and $Player2 != null){
			            $gamecfg->set("turn", $Player2);
						$gamecfg->save();
						if($gamecfg->get("knast2") !== true){
					        $player2->getInventory()->setItem(0, $wuerfeln);
                            $player2->getInventory()->setItem(1, $kaufen);
                            $player2->getInventory()->setItem(2, $bauen);
                            $player2->getInventory()->setItem(3, $hypo);
                            $player2->getInventory()->setItem(4, $handeln);
							$player2->getInventory()->setItem(5, $pay);
                            $player2->getInventory()->setItem(6, $endturn);
					        $player2->getInventory()->setItem(7, $info);
                            $player2->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player2->getInventory()->setItem(0, $wuerfeln);
						    $player2->getInventory()->setItem(2, $freikaufen);
						    $player2->getInventory()->setItem(6, $endturn);
                            $player2->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
					    }
					}elseif($Player1 == null and $Player2 == null){
			            $gamecfg->set("turn", $Player3);
						$gamecfg->save();
						if($gamecfg->get("knast3") !== true){
					        $player3->getInventory()->setItem(0, $wuerfeln);
                            $player3->getInventory()->setItem(1, $kaufen);
                            $player3->getInventory()->setItem(2, $bauen);
                            $player3->getInventory()->setItem(3, $hypo);
                            $player3->getInventory()->setItem(4, $handeln);
							$player3->getInventory()->setItem(5, $pay);
                            $player3->getInventory()->setItem(6, $endturn);
					        $player3->getInventory()->setItem(7, $info);
                            $player3->getInventory()->setItem(8, $giveup);
					        $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup); 
					    }else{
						    $player3->getInventory()->setItem(0, $wuerfeln);
						    $player3->getInventory()->setItem(2, $freikaufen);
						    $player3->getInventory()->setItem(6, $endturn);
                            $player3->getInventory()->setItem(8, $giveup);
						    $p->getInventory()->setItem(7, $info);
                            $p->getInventory()->setItem(8, $giveup);
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