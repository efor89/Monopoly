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

class Anmelden implements Listener{

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
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat sich für das Spiel als Spieler 1 angemeldet.");
		            }elseif($players->get("player1") != null){
			            if($players->get("player2") == null){
				            $players->set("player2", $name);
			                $players->save();
						    $p->getInventory()->clearAll();
                            $anmelden = Item::get(399, 0, 1);
                            $anmelden->setCustomName("§aSpiel Starten");
                            $p->getInventory()->setItem(0, $anmelden);
							Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat sich für das Spiel als Spieler 2 angemeldet.");
			            }elseif($players->get("player2") != null){
				            if($players->get("player3") == null){
				                $players->set("player3", $name);
			                    $players->save();
							    $p->getInventory()->clearAll();
                                $anmelden = Item::get(399, 0, 1);
                                $anmelden->setCustomName("§aSpiel Starten");
                                $p->getInventory()->setItem(0, $anmelden);
								Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat sich für das Spiel als Spieler 3 angemeldet.");
			                }elseif($players->get("player3") != null){
					            if($players->get("player4") == null){
				                    $players->set("player4", $name);
			                        $players->save();
							    	$p->getInventory()->clearAll();
                                    $anmelden = Item::get(399, 0, 1);
                                    $anmelden->setCustomName("§aSpiel Starten");
                                    $p->getInventory()->setItem(0, $anmelden);
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat sich für das Spiel als Spieler 4 angemeldet.");
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
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}