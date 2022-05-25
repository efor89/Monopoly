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
        $name = $p->getName();
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
		$info = Item::get(340, 0, 1);
        $info->setCustomName("§7Infos");
		$giveup = Item::get(355, 14, 1);
        $giveup->setCustomName("§cAufgeben/Bankrott");
        $p->getInventory()->setItem(0, $wuerfeln);
        $p->getInventory()->setItem(1, $kaufen);
        $p->getInventory()->setItem(2, $bauen);
		$p->getInventory()->setItem(3, $hypo);
        $p->getInventory()->setItem(4, $handeln);
		$p->getInventory()->setItem(6, $info);
		$p->getInventory()->setItem(8, $giveup);
		EconomyAPI::getInstance()->setMoney($p, 40000);
		$config = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		$mconfig = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		if($config->get("player1") == null){
			$config->set("player1", $name);
			$config->save();
		}elseif($config->get("player1") !== null){
			if($config->get("player2") == null){
				$config->set("player2", $name);
			    $config->save();
			}elseif($config->get("player2") !== null){
				if($config->get("player3") == null){
				    $config->set("player3", $name);
			        $config->save();
			    }elseif($config->get("player3") !== null){
					if($config->get("player4") == null){
				        $config->set("player4", $name);
			            $config->save();
			        }
				}
			}
		}
    }
	
	public function onPlayerQuit(PlayerQuitEvent $ev){
		$player = $ev->getPlayer();
		EconomyAPI::getInstance()->setMoney($player, 0);
		$config = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		if($player->getName() == $config->get("player1")){
			$config->set("player1", null);
			$config->save();
		}elseif($player->getName() == $config->get("player2")){
			$config->set("player2", null);
			$config->save();
		}elseif($player->getName() == $config->get("player3")){
			$config->set("player3", null);
			$config->save();
		}elseif($player->getName() == $config->get("player4")){
			$config->set("player4", null);
			$config->save();
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
        if(!$player->isOP()){
            $ev->setCancelled(true);
		}
    }
	
	public function onBlockBreak(BlockBreakEvent $ev){
		$player = $ev->getPlayer();
		if(!$player->isOP()){
            $ev->setCancelled(true);
		}
    }
  
    public function onInteract(PlayerInteractEvent $ev){
        $p = $ev->getPlayer();
        $item = $ev->getItem();
		$config = new Config($this->plugin->getDataFolder().'monopoly.yml', Config::YAML);
		$players = new Config($this->plugin->getDataFolder().'player.yml', Config::YAML);
		$Player1 = $players->get("player1");
		$Player2 = $players->get("player2");
		$Player3 = $players->get("player3");
		$Player4 = $players->get("player4");
		if($p->getName() === $Player1){
		    $feld = $config->getNested("coords1.".$p->getPosition());
		}elseif($p->getName() === $Player2){
			$feld = $config->getNested("coords2.".$p->getPosition());
		}elseif($p->getName() === $Player3){
			$feld = $config->getNested("coords3.".$p->getPosition());
		}elseif($p->getName() === $Player4){
			$feld = $config->getNested("coords4.".$p->getPosition());
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
		if($item->getId() === 340) {
            if($item->getName() === "§7Infos") {
                $p->sendMessage("info");
            }
        }
		if($item->getId() === 355) {
            if($item->getName() === "§cAufgeben/Bankrott") {
                $p->sendMessage("Aufgeben/Bankrott");
				EconomyAPI::getInstance()->setMoney($player, 0);
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
		        $info = Item::get(340, 0, 1);
                $info->setCustomName("§7Infos");
		        $giveup = Item::get(355, 14, 1);
                $giveup->setCustomName("§cAufgeben/Bankrott");
                $p->getInventory()->setItem(0, $wuerfeln);
                $p->getInventory()->setItem(1, $kaufen);
                $p->getInventory()->setItem(2, $bauen);
		        $p->getInventory()->setItem(3, $hypo);
                $p->getInventory()->setItem(4, $handeln);
		        $p->getInventory()->setItem(6, $info);
		        $p->getInventory()->setItem(8, $giveup);
            }
        }
		if(!$player->isOP()){
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