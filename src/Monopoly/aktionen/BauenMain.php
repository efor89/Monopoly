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

class BauenMain implements Listener{

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
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}