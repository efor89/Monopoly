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

class FreiKaufen implements Listener{

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
		if($item->getId() === 264) {
            if($item->getName() === "§6Frei Kaufen") {
				EconomyAPI::getInstance()->reduceMoney($p, 1000);
				$gamecfg->set("freiparken", $gamecfg->get("freiparken") + 1000);
				$gamecfg->save();
				if($p->getName() == $Player1){
				    $gamecfg->set("knast1", false);
					$gamecfg->set("knast-turn1", 0);
					$gamecfg->save();
				}elseif($p->getName() == $Player2){
					$gamecfg->set("knast-turn2", 0);
				    $gamecfg->set("knast2", false);
					$gamecfg->save();
				}elseif($p->getName() == $Player3){
				    $gamecfg->set("knast3", false);
					$gamecfg->set("knast-turn3", 0);
					$gamecfg->save();
				}elseif($p->getName() == $Player4){
				    $gamecfg->set("knast4", false);
					$gamecfg->set("knast-turn4", 0);
					$gamecfg->save();
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
				$p->getInventory()->setItem(0, $wuerfeln);
                $p->getInventory()->setItem(1, $kaufen);
                $p->getInventory()->setItem(2, $bauen);
                $p->getInventory()->setItem(3, $hypo);
                $p->getInventory()->setItem(4, $handeln);
				$p->getInventory()->setItem(5, $pay);
                $p->getInventory()->setItem(6, $endturn);
			    $p->getInventory()->setItem(7, $info);
                $p->getInventory()->setItem(8, $giveup);
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}