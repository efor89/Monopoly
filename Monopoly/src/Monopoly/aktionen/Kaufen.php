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

class Kaufen implements Listener{

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
		if($item->getId() === 266) {
            if($item->getName() === "§6Kaufen") {
                $playerMoney = EconomyAPI::getInstance()->myMoney($p);
				if($p->getName() == $Player1){
				    $feld = $gamecfg->get("player1");
					$check = $gamecfg->get("player1");
				}elseif($p->getName() == $Player2){
				    $feld = $gamecfg->get("player2");
					$check = $gamecfg->get("player2");
				}elseif($p->getName() == $Player3){
				    $feld = $gamecfg->get("player3");
					$check = $gamecfg->get("player3");
				}elseif($p->getName() == $Player4){
				    $feld = $gamecfg->get("player4");
					$check = $gamecfg->get("player4");
				}
				$kosten = $config->getNested($feld.".buy");
				$strassenName = $config->getNested($feld.".name");
				if($check == 2 or $check == 4 or $check == 6 or $check == 7 or $check == 9 or $check == 10 or $check == 12 or $check == 13 or $check == 14 or $check == 15 or $check == 16 or $check == 17 or $check == 19 or $check == 20 or $check == 22 or $check == 24 or $check == 25 or $check == 26 or $check == 27 or $check == 28 or $check == 29 or $check == 30 or $check == 32 or $check == 33 or $check == 35 or $check == 36 or $check == 38 or $check == 40){
					if($gamecfg->get($feld) == $p->getName()){
						$p->sendMessage("§bMono§6poly: §cDie Strasse gehört dir bereits!");
						return;
					}
					if($gamecfg->get($feld) == null){
						$KaufenJa = Item::get(266, 0, 1);
                        $KaufenJa->setCustomName("§aKaufen Ja");
						$KaufenNein = Item::get(265, 0, 1);
                        $KaufenNein->setCustomName("§cKaufen Nein");
						$exit = Item::get(331, 14, 1);
                        $exit->setCustomName("§cZurück");
						$p->getInventory()->clearAll();
						$p->getInventory()->setItem(0, $KaufenJa);
						$p->getInventory()->setItem(2, $KaufenNein);
						$p->getInventory()->setItem(8, $exit);
					}else{
						$p->sendMessage("§bMono§6poly: §cDie Strasse gehört bereits einem anderen Spieler!");
					}
				}else{
					$p->sendMessage("§bMono§6poly: §cDu kannst hier nichts kaufen!");
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}