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
				}elseif($p->getName() == $Player2){
				    $feld = $gamecfg->get("player2");
				}elseif($p->getName() == $Player3){
				    $feld = $gamecfg->get("player3");
				}elseif($p->getName() == $Player4){
				    $feld = $gamecfg->get("player4");
				}
				$kosten = $config->getNested($feld.".buy");
				$strassenName = $config->getNested($feld.".name");
				if($gamecfg->get("player1") == 2 or $gamecfg->get("player1") == 4 or $gamecfg->get("player1") == 6 or $gamecfg->get("player1") == 7 or $gamecfg->get("player1") == 9 or $gamecfg->get("player1") == 10 or $gamecfg->get("player1") == 12 or $gamecfg->get("player1") == 13 or $gamecfg->get("player1") == 14 or $gamecfg->get("player1") == 15 or $gamecfg->get("player1") == 16 or $gamecfg->get("player1") == 17 or $gamecfg->get("player1") == 19 or $gamecfg->get("player1") == 20 or $gamecfg->get("player1") == 22 or $gamecfg->get("player1") == 24 or $gamecfg->get("player1") == 25 or $gamecfg->get("player1") == 26 or $gamecfg->get("player1") == 27 or $gamecfg->get("player1") == 28 or $gamecfg->get("player1") == 29 or $gamecfg->get("player1") == 30 or $gamecfg->get("player1") == 32 or $gamecfg->get("player1") == 33 or $gamecfg->get("player1") == 35 or $gamecfg->get("player1") == 36 or $gamecfg->get("player1") == 38 or $gamecfg->get("player1") == 40){
					if($gamecfg->get($feld) == $p->getName()){
						$p->sendMessage("§bMono§6poly: §cDie Strasse gehört dir bereits!");
						return;
					}
				    if($playerMoney >= $kosten){
					    EconomyAPI::getInstance()->reduceMoney($p, $kosten);
						Server::getInstance()->broadcastMessage("§bMono§6poly: §d".$p->getName()." §ahat die Strasse §d".$strassenName." §agekauft.");
						$gamecfg->set($feld, $p->getName());
						$gamecfg->save();
				    }else{
					    $p->sendMessage("§bMono§6poly: §cDu hast nicht genug Geld um die Strasse zu kaufen!");
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