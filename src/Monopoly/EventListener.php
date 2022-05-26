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
        if($Player1 != null and $Player2 != null and $Player3 == null and $Player4 == null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player2", null);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() !== $player1->getName()){
			    $player1->getInventory()->clearAll();
			    $player1->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}else{
				$player2->getInventory()->clearAll();
			    $player2->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 != null and $Player2 == null and $Player3 != null and $Player4 == null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player3", null);
			$players->save();
			$player2->getInventory()->clearAll();
			$player2->getInventory()->setItem(4, $anmelden);
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() !== $player1->getName()){
				$player1->getInventory()->clearAll();
			    $player1->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}else{
				$player3->getInventory()->clearAll();
			    $player3->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 != null and $Player2 == null and $Player3 == null and $Player4 != null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
		    $gamecfg->save();
			$players->set("player1", null);
			$players->set("player4", null);
			$players->save();
			$player2->getInventory()->clearAll();
			$player2->getInventory()->setItem(4, $anmelden);
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() !== $player1->getName()){
				$player1->getInventory()->clearAll();
			    $player1->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player1." §ahat das Spiel Gewonnen.");
			}else{
				$player4->getInventory()->clearAll();
			    $player4->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}
	    }elseif($Player1 == null and $Player2 != null and $Player3 != null and $Player4 == null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
		    $gamecfg->save();
			$players->set("player2", null);
			$players->set("player3", null);
			$gamecfg->set("wurf", false);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() !== $player2->getName()){
				$player2->getInventory()->clearAll();
			    $player2->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}else{
				$player3->getInventory()->clearAll();
			    $player3->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 == null and $Player2 != null and $Player3 == null and $Player4 != null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
		    $gamecfg->save();
			$players->set("player2", null);
			$players->set("player4", null);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() !== $player2->getName()){
				$player2->getInventory()->clearAll();
			    $player2->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}else{
				$player4->getInventory()->clearAll();
			    $player4->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player2." §ahat das Spiel Gewonnen.");
			}
		}elseif($Player1 == null and $Player2 == null and $Player3 != null and $Player4 != null){
			$gamecfg->set("start", false);
			$gamecfg->set("turn", null);
			$gamecfg->set("wurf", false);
		    $gamecfg->save();
			$players->set("player3", null);
			$players->set("player4", null);
			$players->save();
			if($gamecfg->get("start") !== true){
				return;
			}
			if($p->getName() !== $player3->getName()){
				$player3->getInventory()->clearAll();
			    $player3->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player3." §ahat das Spiel Gewonnen.");
			}else{
				$player4->getInventory()->clearAll();
			    $player4->getInventory()->setItem(4, $anmelden);
				Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$Player4." §ahat das Spiel Gewonnen.");
			}
		}
		if($p->getName() == $players->get("player1")){
			$players->set("player1", null);
	        $players->save();
			if($Player2 != null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player2->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player2->getInventory()->clearAll();
				    $player2->getInventory()->setItem(0, $wuerfeln);
                    $player2->getInventory()->setItem(1, $kaufen);
                    $player2->getInventory()->setItem(2, $bauen);
                    $player2->getInventory()->setItem(3, $hypo);
                    $player2->getInventory()->setItem(4, $handeln);
                    $player2->getInventory()->setItem(6, $endturn);
			        $player2->getInventory()->setItem(7, $info);
                    $player2->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player2 != null and $Player3 == null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player2->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player3->getInventory()->clearAll();
				    $player3->getInventory()->setItem(0, $wuerfeln);
                    $player3->getInventory()->setItem(1, $kaufen);
                    $player3->getInventory()->setItem(2, $bauen);
                    $player3->getInventory()->setItem(3, $hypo);
                    $player3->getInventory()->setItem(4, $handeln);
                    $player3->getInventory()->setItem(6, $endturn);
			        $player3->getInventory()->setItem(7, $info);
                    $player3->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player2 == null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player3->getInventory()->clearAll();
				    $player3->getInventory()->setItem(0, $wuerfeln);
                    $player3->getInventory()->setItem(1, $kaufen);
                    $player3->getInventory()->setItem(2, $bauen);
                    $player3->getInventory()->setItem(3, $hypo);
                    $player3->getInventory()->setItem(4, $handeln);
                    $player3->getInventory()->setItem(6, $endturn);
			        $player3->getInventory()->setItem(7, $info);
                    $player3->getInventory()->setItem(8, $giveup);
				}
			}
        }elseif($p->getName() == $players->get("player2")){
	        $players->set("player2", null);
	        $players->save();
			if($Player1 != null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player3->getInventory()->clearAll();
				    $player3->getInventory()->setItem(0, $wuerfeln);
                    $player3->getInventory()->setItem(1, $kaufen);
                    $player3->getInventory()->setItem(2, $bauen);
                    $player3->getInventory()->setItem(3, $hypo);
                    $player3->getInventory()->setItem(4, $handeln);
                    $player3->getInventory()->setItem(6, $endturn);
			        $player3->getInventory()->setItem(7, $info);
                    $player3->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 != null and $Player3 == null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player4->getInventory()->clearAll();
				    $player4->getInventory()->setItem(0, $wuerfeln);
                    $player4->getInventory()->setItem(1, $kaufen);
                    $player4->getInventory()->setItem(2, $bauen);
                    $player4->getInventory()->setItem(3, $hypo);
                    $player4->getInventory()->setItem(4, $handeln);
                    $player4->getInventory()->setItem(6, $endturn);
			        $player4->getInventory()->setItem(7, $info);
                    $player4->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 == null and $Player3 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player4->getInventory()->clearAll();
				    $player4->getInventory()->setItem(0, $wuerfeln);
                    $player4->getInventory()->setItem(1, $kaufen);
                    $player4->getInventory()->setItem(2, $bauen);
                    $player4->getInventory()->setItem(3, $hypo);
                    $player4->getInventory()->setItem(4, $handeln);
                    $player4->getInventory()->setItem(6, $endturn);
			        $player4->getInventory()->setItem(7, $info);
                    $player4->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 != null and $Player3 != null and $Player4 == null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player3->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player4->getInventory()->clearAll();
				    $player4->getInventory()->setItem(0, $wuerfeln);
                    $player4->getInventory()->setItem(1, $kaufen);
                    $player4->getInventory()->setItem(2, $bauen);
                    $player4->getInventory()->setItem(3, $hypo);
                    $player4->getInventory()->setItem(4, $handeln);
                    $player4->getInventory()->setItem(6, $endturn);
			        $player4->getInventory()->setItem(7, $info);
                    $player4->getInventory()->setItem(8, $giveup);
				}
			}
        }elseif($p->getName() == $players->get("player3")){
	        $players->set("player3", null);
	        $players->save();
			if($Player1 != null and $Player2 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player4->getInventory()->clearAll();
				    $player4->getInventory()->setItem(0, $wuerfeln);
                    $player4->getInventory()->setItem(1, $kaufen);
                    $player4->getInventory()->setItem(2, $bauen);
                    $player4->getInventory()->setItem(3, $hypo);
                    $player4->getInventory()->setItem(4, $handeln);
                    $player4->getInventory()->setItem(6, $endturn);
			        $player4->getInventory()->setItem(7, $info);
                    $player4->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 != null and $Player2 == null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player1->getInventory()->clearAll();
				    $player1->getInventory()->setItem(0, $wuerfeln);
                    $player1->getInventory()->setItem(1, $kaufen);
                    $player1->getInventory()->setItem(2, $bauen);
                    $player1->getInventory()->setItem(3, $hypo);
                    $player1->getInventory()->setItem(4, $handeln);
                    $player1->getInventory()->setItem(6, $endturn);
			        $player1->getInventory()->setItem(7, $info);
                    $player1->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 != null and $Player2 != null and $Player4 == null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player1->getInventory()->clearAll();
				    $player1->getInventory()->setItem(0, $wuerfeln);
                    $player1->getInventory()->setItem(1, $kaufen);
                    $player1->getInventory()->setItem(2, $bauen);
                    $player1->getInventory()->setItem(3, $hypo);
                    $player1->getInventory()->setItem(4, $handeln);
                    $player1->getInventory()->setItem(6, $endturn);
			        $player1->getInventory()->setItem(7, $info);
                    $player1->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 == null and $Player2 != null and $Player4 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player4->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player1->getInventory()->clearAll();
				    $player1->getInventory()->setItem(0, $wuerfeln);
                    $player1->getInventory()->setItem(1, $kaufen);
                    $player1->getInventory()->setItem(2, $bauen);
                    $player1->getInventory()->setItem(3, $hypo);
                    $player1->getInventory()->setItem(4, $handeln);
                    $player1->getInventory()->setItem(6, $endturn);
			        $player1->getInventory()->setItem(7, $info);
                    $player1->getInventory()->setItem(8, $giveup);
				}
			}
        }elseif($p->getName() == $players->get("player4")){
	        $players->set("player4", null);
	        $players->save();
			if($Player1 != null and $Player2 != null and $Player3 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player1->getInventory()->clearAll();
				    $player1->getInventory()->setItem(0, $wuerfeln);
                    $player1->getInventory()->setItem(1, $kaufen);
                    $player1->getInventory()->setItem(2, $bauen);
                    $player1->getInventory()->setItem(3, $hypo);
                    $player1->getInventory()->setItem(4, $handeln);
                    $player1->getInventory()->setItem(6, $endturn);
			        $player1->getInventory()->setItem(7, $info);
                    $player1->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 != null and $Player2 == null and $Player3 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player2->getInventory()->clearAll();
				    $player2->getInventory()->setItem(0, $wuerfeln);
                    $player2->getInventory()->setItem(1, $kaufen);
                    $player2->getInventory()->setItem(2, $bauen);
                    $player2->getInventory()->setItem(3, $hypo);
                    $player2->getInventory()->setItem(4, $handeln);
                    $player2->getInventory()->setItem(6, $endturn);
			        $player2->getInventory()->setItem(7, $info);
                    $player2->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 != null and $Player2 != null and $Player3 == null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player1->getName());
				    $gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player2->getInventory()->clearAll();
				    $player2->getInventory()->setItem(0, $wuerfeln);
                    $player2->getInventory()->setItem(1, $kaufen);
                    $player2->getInventory()->setItem(2, $bauen);
                    $player2->getInventory()->setItem(3, $hypo);
                    $player2->getInventory()->setItem(4, $handeln);
                    $player2->getInventory()->setItem(6, $endturn);
			        $player2->getInventory()->setItem(7, $info);
                    $player2->getInventory()->setItem(8, $giveup);
				}
			}elseif($Player1 == null and $Player2 != null and $Player3 != null){
				if($p->getName() == $gamecfg->get("turn")){
				    $gamecfg->set("turn", $player2->getName());
			    	$gamecfg->set("wurf", false);
				    $gamecfg->set("pasch", 0);
				    $gamecfg->save();
				    $player2->getInventory()->clearAll();
				    $player2->getInventory()->setItem(0, $wuerfeln);
                    $player2->getInventory()->setItem(1, $kaufen);
                    $player2->getInventory()->setItem(2, $bauen);
                    $player2->getInventory()->setItem(3, $hypo);
                    $player2->getInventory()->setItem(4, $handeln);
                    $player2->getInventory()->setItem(6, $endturn);
			        $player2->getInventory()->setItem(7, $info);
                    $player2->getInventory()->setItem(8, $giveup);
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
		            }elseif($players->get("player1") != null){
			            if($players->get("player2") == null){
				            $players->set("player2", $name);
			                $players->save();
						    $p->getInventory()->clearAll();
                            $anmelden = Item::get(399, 0, 1);
                            $anmelden->setCustomName("§aSpiel Starten");
                            $p->getInventory()->setItem(0, $anmelden);
			            }elseif($players->get("player2") != null){
				            if($players->get("player3") == null){
				                $players->set("player3", $name);
			                    $players->save();
							    $p->getInventory()->clearAll();
                                $anmelden = Item::get(399, 0, 1);
                                $anmelden->setCustomName("§aSpiel Starten");
                                $p->getInventory()->setItem(0, $anmelden);
			                }elseif($players->get("player3") != null){
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
						$gamecfg->set("player1", 1);
						$gamecfg->set("player2", 1);
						$gamecfg->set("player3", 1);
						$gamecfg->set("player4", 1);
						$gamecfg->set("pasch", 0);
						$gamecfg->save();
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
				$points = $point1 + $point2;
				if($gamecfg->get("wurf") !== true){
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
							if($p->getName() == $Player1){
							    if($gamecfg->get("knast1") !== false){
									$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									$xlast = $config->getNested("coords1.knastx");
								    $zlast = $config->getNested("coords1.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
									        $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
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
									    $p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
										$xlast = $config->getNested("coords1.knastx");
								        $zlast = $config->getNested("coords1.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."x");
			                            $z = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
										$gamecfg->set("player1", ($gamecfg->get("player1") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}elseif($p->getName() == $Player2){
							    if($gamecfg->get("knast2") !== false){
									$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									$xlast = $config->getNested("coords2.knastx");
								    $zlast = $config->getNested("coords2.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
									        $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
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
									    $p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
										$xlast = $config->getNested("coords2.knastx");
								        $zlast = $config->getNested("coords2.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."x");
			                            $z = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
										$gamecfg->set("player2", ($gamecfg->get("player2") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}elseif($p->getName() == $Player3){
							    if($gamecfg->get("knast3") !== false){
									$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									$xlast = $config->getNested("coords3.knastx");
								    $zlast = $config->getNested("coords3.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
									        $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
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
									    $p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
										$xlast = $config->getNested("coords3.knastx");
								        $zlast = $config->getNested("coords3.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."x");
			                            $z = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(91, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
										$gamecfg->set("player3", ($gamecfg->get("player3") + $points) - 40);
										$gamecfg->set("pasch", $gamecfg->get("pasch") + 1);
										$gamecfg->save();
									}
									Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat eine §d".$point1 + $point2." §aGewürfelt da es ein Pasch war kann §d".$p->getName()." §anochmal.");
								}
							}elseif($p->getName() == $Player4){
							    if($gamecfg->get("knast4") !== false){
									$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									$xlast = $config->getNested("coords4.knastx");
								    $zlast = $config->getNested("coords4.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
									        $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
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
									    $p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
										$xlast = $config->getNested("coords4.knastx");
								        $zlast = $config->getNested("coords4.knastz");
									    $p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
										EconomyAPI::getInstance()->addMoney($p, 4000);
										$x = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."x");
			                            $z = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."z");
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
									    $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
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
								$p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}elseif($p->getName() == $Player2){
							    $gamecfg->set("knast2", true);
								$gamecfg->save();
								$x = $config->getNested("coords2.knastx");
								$z = $config->getNested("coords2.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
								$p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}elseif($p->getName() == $Player3){
							    $gamecfg->set("knast3", true);
								$gamecfg->save();
								$x = $config->getNested("coords3.knastx");
								$z = $config->getNested("coords3.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(91, 0));
								$p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
								$gamecfg->set("player4", 11);
							    $gamecfg->save();
							}elseif($p->getName() == $Player4){
							    $gamecfg->set("knast4", true);
								$gamecfg->save();
								$x = $config->getNested("coords4.knastx");
								$z = $config->getNested("coords4.knastz");
								$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
								$p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
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
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									$xlast = $config->getNested("coords1.knastx");
								    $zlast = $config->getNested("coords1.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
								        $p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
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
									$p->getLevel()->setBlock(new Vector3($x1, $y, $z1), Block::get(165, 0));
									$p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
									$gamecfg->set("player1", $gamecfg->get("player1") + $points);
									$gamecfg->set("wurf", true);
							        $gamecfg->save();
								}else{
									if((($gamecfg->get("player1") + $points) - 40) == 5){
										EconomyAPI::getInstance()->reduceMoney($p, 4000);
									    $gamecfg->set("freiparken", $gamecfg->get("freiparken") + 4000);
									    $gamecfg->save();
										Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §aist auf Einkommensteuer gekommen und muss §d4000§a$ bezahlen.");
									}
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."x");
			                        $z = $config->getNested("coords1.".(($gamecfg->get("player1") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
									$p->getLevel()->setBlock(new Vector3($xlast1, $y, $zlast1), Block::get(1, 0));
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
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									$xlast = $config->getNested("coords2.knastx");
								    $zlast = $config->getNested("coords2.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
								        $p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
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
									$p->getLevel()->setBlock(new Vector3($x2, $y, $z2), Block::get(19, 0));
									$p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
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
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."x");
			                        $z = $config->getNested("coords2.".(($gamecfg->get("player2") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(19, 0));
									$p->getLevel()->setBlock(new Vector3($xlast2, $y, $zlast2), Block::get(1, 0));
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
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									$xlast = $config->getNested("coords3.knastx");
								    $zlast = $config->getNested("coords3.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
								        $p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
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
									$p->getLevel()->setBlock(new Vector3($x3, $y, $z3), Block::get(91, 0));
									$p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
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
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."x");
			                        $z = $config->getNested("coords3.".(($gamecfg->get("player3") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(91, 0));
									$p->getLevel()->setBlock(new Vector3($xlast3, $y, $zlast3), Block::get(1, 0));
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
									EconomyAPI::getInstance()->reduceMoney($p, 1000);
									$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									$xlast = $config->getNested("coords4.knastx");
								    $zlast = $config->getNested("coords4.knastz");
									$p->getLevel()->setBlock(new Vector3($xlast, $y, $zlast), Block::get(1, 0));
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
										$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(165, 0));
								        $p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
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
									$p->getLevel()->setBlock(new Vector3($x4, $y, $z4), Block::get(170, 0));
									$p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
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
									EconomyAPI::getInstance()->addMoney($p, 4000);
									$x = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."x");
			                        $z = $config->getNested("coords4.".(($gamecfg->get("player4") + $points) - 40)."z");
									$p->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(170, 0));
									$p->getLevel()->setBlock(new Vector3($xlast4, $y, $zlast4), Block::get(1, 0));
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
		if($item->getId() === 266) {
            if($item->getName() === "§6Kaufen") {
                $playerMoney = EconomyAPI::getInstance()->myMoney($p);
				$p->sendMessage("§cKommt noch!");
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
		$playerMoney = EconomyAPI::getInstance()->myMoney($p);
		if($item->getId() === 236) {
            if($item->getName() === "§aHaus Bauen") {
                $p->sendMessage("§cKommt noch!");
            }
        }
		if($item->getId() === 236) {
            if($item->getName() === "§aHotel Bauen") {
                $p->sendMessage("§cKommt noch!");
            }
        }
		if($item->getId() === 278) {
            if($item->getName() === "§6Abbauen") {
                $p->sendMessage("§cKommt noch!");
            }
        }
		if($item->getId() === 46) {
            if($item->getName() === "§eHypothek") {
                $p->sendMessage("§cKommt noch!");
            }
        }
		if($item->getId() === 54) {
            if($item->getName() === "§dHandeln") {
                $p->sendMessage("§cKommt noch!");
            }
        }
		if($item->getId() === 208) {
            if($item->getName() === "§3Zug Beenden") {
                $p->getInventory()->clearAll();
				$wuerfeln = Item::get(236, 0, 1);
                $wuerfeln->setCustomName("§aWürfeln");
                $kaufen = Item::get(266, 0, 1);
                $kaufen->setCustomName("§6Kaufen");
				$freikaufen = Item::get(266, 0, 1);
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
				if($p->getName() === $Player1){
		            $gamecfg->set("turn", $Player2);
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
					if($gamecfg->get("knast2") !== true){
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
					}else{
						$player2->getInventory()->setItem(0, $wuerfeln);
						$player2->getInventory()->setItem(2, $freikaufen);
						$player2->getInventory()->setItem(6, $endturn);
                        $player2->getInventory()->setItem(8, $giveup);
						$p->getInventory()->setItem(7, $info);
                        $p->getInventory()->setItem(8, $giveup);
					}
		        }elseif($p->getName() === $Player2){
			        $gamecfg->set("turn", $Player3);
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
					if($gamecfg->get("knast3") !== true){
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
					}else{
						$player3->getInventory()->setItem(0, $wuerfeln);
						$player3->getInventory()->setItem(2, $freikaufen);
						$player3->getInventory()->setItem(6, $endturn);
                        $player3->getInventory()->setItem(8, $giveup);
						$p->getInventory()->setItem(7, $info);
                        $p->getInventory()->setItem(8, $giveup);
					}					
		        }elseif($p->getName() === $Player3){
			        $gamecfg->set("turn", $Player4);
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
					if($gamecfg->get("knast4") !== true){
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
					}else{
						$player4->getInventory()->setItem(0, $wuerfeln);
						$player4->getInventory()->setItem(2, $freikaufen);
						$player4->getInventory()->setItem(6, $endturn);
                        $player4->getInventory()->setItem(8, $giveup);
						$p->getInventory()->setItem(7, $info);
                        $p->getInventory()->setItem(8, $giveup);
					}
		        }elseif($p->getName() === $Player4){
			        $gamecfg->set("turn", $Player1);
					$gamecfg->set("wurf", false);
					$gamecfg->set("pasch", 0);
				    $gamecfg->save();
					if($gamecfg->get("knast1") !== true){
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
					}else{
						$player1->getInventory()->setItem(0, $wuerfeln);
						$player1->getInventory()->setItem(2, $freikaufen);
						$player1->getInventory()->setItem(6, $endturn);
                        $player1->getInventory()->setItem(8, $giveup);
						$p->getInventory()->setItem(7, $info);
                        $p->getInventory()->setItem(8, $giveup);
					}
		        }
            }
        }
		if($item->getId() === 266) {
            if($item->getName() === "§6Frei Kaufen") {
				EconomyAPI::getInstance()->reduceMoney($p, 1000);
				$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
				$gamecfg->save();
				if($p->getName() === $Player1){
				    $gamecfg->set("knast1", false);
				}elseif($p->getName() === $Player2){
				    $gamecfg->set("knast2", false);
				}elseif($p->getName() === $Player3){
				    $gamecfg->set("knast3", false);
				}elseif($p->getName() === $Player4){
				    $gamecfg->set("knast4", false);
				}
				$p->getInventory()->clearAll();
				$wuerfeln = Item::get(236, 0, 1);
                $wuerfeln->setCustomName("§aWürfeln");
                $kaufen = Item::get(266, 0, 1);
                $kaufen->setCustomName("§6Kaufen");
				$freikaufen = Item::get(266, 0, 1);
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
				$p->getInventory()->setItem(0, $wuerfeln);
                $p->getInventory()->setItem(1, $kaufen);
                $p->getInventory()->setItem(2, $bauen);
                $p->getInventory()->setItem(3, $hypo);
                $p->getInventory()->setItem(4, $handeln);
                $p->getInventory()->setItem(6, $endturn);
			    $p->getInventory()->setItem(7, $info);
                $p->getInventory()->setItem(8, $giveup);
            }
        }
		if($item->getId() === 340) {
            if($item->getName() === "§7Infos") {
                $p->sendMessage("§cKommt noch!");
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
				foreach(Server::getInstance()->getOnlinePlayers() as $player){
				    if($players->get("player1") == null and $players->get("player2") == null and $players->get("player3") != null and $players->get("player4") != null){
					    $player4->getInventory()->clearAll();
						$player3->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player4->getInventory()->setItem(4, $anmelden);
						$player3->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() !== $Player4){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat das Spiel Gewonnen.");
			            }
						$players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
						$players->save();
						return;
				    }elseif($players->get("player1") == null and $players->get("player3") == null and $players->get("player2") != null and $players->get("player4") != null){
					    $player2->getInventory()->clearAll();
						$player4->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player2->getInventory()->setItem(4, $anmelden);
						$player4->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() !== $Player2){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat das Spiel Gewonnen.");
			            }
						$players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
						$players->save();
						return;
				    }elseif($players->get("player1") == null and $players->get("player4") == null and $players->get("player3") != null and $players->get("player2") != null){
					    $player2->getInventory()->clearAll();
						$player3->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player2->getInventory()->setItem(4, $anmelden);
						$player3->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() !== $Player2){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat das Spiel Gewonnen.");
			            }
						$players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
					    $players->save();
						return;
				    }elseif($players->get("player2") == null and $players->get("player3") == null and $players->get("player1") != null and $players->get("player4") != null){
					    $player4->getInventory()->clearAll();
						$player1->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player4->getInventory()->setItem(4, $anmelden);
						$player1->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() !== $Player4){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player4->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat das Spiel Gewonnen.");
			            }
						$players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
						$players->save();
						return;
				    }elseif($players->get("player2") == null and $players->get("player4") == null and $players->get("player3") != null and $players->get("player1") != null){
					    $player3->getInventory()->clearAll();
						$player1->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player3->getInventory()->setItem(4, $anmelden);
						$player1->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() !== $Player3){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player3->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat das Spiel Gewonnen.");
			            }
						$players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
						$players->save();
						return;
				    }elseif($players->get("player3") == null and $players->get("player4") == null and $players->get("player1") != null and $players->get("player2") != null){
					    $player2->getInventory()->clearAll();
						$player1->getInventory()->clearAll();
                        $anmelden = Item::get(421, 0, 1);
                        $anmelden->setCustomName("§aAls Spieler Anmelden");
                        $player2->getInventory()->setItem(4, $anmelden);
						$player1->getInventory()->setItem(4, $anmelden);
						$gamecfg->set("start", false);
						$gamecfg->set("turn", null);
					    $gamecfg->save();
						if($p->getName() !== $Player2){
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player2->getName()." §ahat das Spiel Gewonnen.");
			            }else{
				            Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$player1->getName()." §ahat das Spiel Gewonnen.");
			            }
						$players->set("player1", null);
					    $players->set("player2", null);
					    $players->set("player3", null);
					    $players->set("player4", null);
						$players->save();
						return;
				    }
				}
				if($p->getName() == $Player1){
			        $players->set("player1", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(4, $anmelden);
					if($Player2 != null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player2->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player2->getInventory()->clearAll();
				            $player2->getInventory()->setItem(0, $wuerfeln);
                            $player2->getInventory()->setItem(1, $kaufen);
                            $player2->getInventory()->setItem(2, $bauen);
                            $player2->getInventory()->setItem(3, $hypo);
                            $player2->getInventory()->setItem(4, $handeln);
                            $player2->getInventory()->setItem(6, $endturn);
			                $player2->getInventory()->setItem(7, $info);
                            $player2->getInventory()->setItem(8, $giveup);
						}
					}elseif($Player2 == null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player3->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player3->getInventory()->clearAll();
				            $player3->getInventory()->setItem(0, $wuerfeln);
                            $player3->getInventory()->setItem(1, $kaufen);
                            $player3->getInventory()->setItem(2, $bauen);
                            $player3->getInventory()->setItem(3, $hypo);
                            $player3->getInventory()->setItem(4, $handeln);
                            $player3->getInventory()->setItem(6, $endturn);
			                $player3->getInventory()->setItem(7, $info);
                            $player3->getInventory()->setItem(8, $giveup);
						}
					}
		        }elseif($p->getName() == $Player2){
			        $players->set("player2", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(4, $anmelden);
					if($Player3 != null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player3->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player3->getInventory()->clearAll();
				            $player3->getInventory()->setItem(0, $wuerfeln);
                            $player3->getInventory()->setItem(1, $kaufen);
                            $player3->getInventory()->setItem(2, $bauen);
                            $player3->getInventory()->setItem(3, $hypo);
                            $player3->getInventory()->setItem(4, $handeln);
                            $player3->getInventory()->setItem(6, $endturn);
			                $player3->getInventory()->setItem(7, $info);
                            $player3->getInventory()->setItem(8, $giveup);
						}
					}elseif($Player3 == null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player4->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player4->getInventory()->clearAll();
				            $player4->getInventory()->setItem(0, $wuerfeln);
                            $player4->getInventory()->setItem(1, $kaufen);
                            $player4->getInventory()->setItem(2, $bauen);
                            $player4->getInventory()->setItem(3, $hypo);
                            $player4->getInventory()->setItem(4, $handeln);
                            $player4->getInventory()->setItem(6, $endturn);
			                $player4->getInventory()->setItem(7, $info);
                            $player4->getInventory()->setItem(8, $giveup);
						}
					}
		        }elseif($p->getName() == $Player3){
			        $players->set("player3", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(4, $anmelden);
					if($Player4 != null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player4->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player4->getInventory()->clearAll();
				            $player4->getInventory()->setItem(0, $wuerfeln);
                            $player4->getInventory()->setItem(1, $kaufen);
                            $player4->getInventory()->setItem(2, $bauen);
                            $player4->getInventory()->setItem(3, $hypo);
                            $player4->getInventory()->setItem(4, $handeln);
                            $player4->getInventory()->setItem(6, $endturn);
			                $player4->getInventory()->setItem(7, $info);
                            $player4->getInventory()->setItem(8, $giveup);
						}
					}elseif($Player4 == null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player1->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player1->getInventory()->clearAll();
				            $player1->getInventory()->setItem(0, $wuerfeln);
                            $player1->getInventory()->setItem(1, $kaufen);
                            $player1->getInventory()->setItem(2, $bauen);
                            $player1->getInventory()->setItem(3, $hypo);
                            $player1->getInventory()->setItem(4, $handeln);
                            $player1->getInventory()->setItem(6, $endturn);
			                $player1->getInventory()->setItem(7, $info);
                            $player1->getInventory()->setItem(8, $giveup);
						}
					}
		        }elseif($p->getName() == $Player4){
			        $players->set("player4", null);
			        $players->save();
					$p->getInventory()->clearAll();
                    $anmelden = Item::get(421, 0, 1);
                    $anmelden->setCustomName("§aAls Spieler Anmelden");
                    $p->getInventory()->setItem(4, $anmelden);
					if($Player1 != null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player1->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player1->getInventory()->clearAll();
				            $player1->getInventory()->setItem(0, $wuerfeln);
                            $player1->getInventory()->setItem(1, $kaufen);
                            $player1->getInventory()->setItem(2, $bauen);
                            $player1->getInventory()->setItem(3, $hypo);
                            $player1->getInventory()->setItem(4, $handeln);
                            $player1->getInventory()->setItem(6, $endturn);
			                $player1->getInventory()->setItem(7, $info);
                            $player1->getInventory()->setItem(8, $giveup);
						}
					}elseif($Player1 == null){
						if($p->getName() == $gamecfg->get("turn")){
							$gamecfg->set("pasch", 0);
						    $gamecfg->set("turn", $player2->getName());
						    $gamecfg->set("wurf", false);
						    $gamecfg->save();
							$player2->getInventory()->clearAll();
				            $player2->getInventory()->setItem(0, $wuerfeln);
                            $player2->getInventory()->setItem(1, $kaufen);
                            $player2->getInventory()->setItem(2, $bauen);
                            $player2->getInventory()->setItem(3, $hypo);
                            $player2->getInventory()->setItem(4, $handeln);
                            $player2->getInventory()->setItem(6, $endturn);
			                $player2->getInventory()->setItem(7, $info);
                            $player2->getInventory()->setItem(8, $giveup);
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
					$p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
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
					$p->getInventory()->setItem(7, $info);
                    $p->getInventory()->setItem(8, $giveup);
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