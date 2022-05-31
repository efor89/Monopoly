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

class MieteBezahlen implements Listener{

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
		if($item->getId() === 371) {
            if($item->getName() === "§6Miete Bezahlen") {
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
				$target = $gamecfg->get($feld);
				$player = Server::getInstance()->getPlayer($target);
			    if($gamecfg->get($feld) === null){
					$p->sendMessage("§bMono§6poly: §cDu musst hier Keine Miete bezahlen!");
					return;
				}
				if($gamecfg->get($feld) == $p->getName()){
					$p->sendMessage("§bMono§6poly: §cDu musst hier Keine Miete bezahlen da es deine Strasse ist!");
					return;
				}
				if($gamecfg->get($feld."hypo") == true){
					$gamecfg->set("miete", false);
					$gamecfg->save();
					$p->sendMessage("§bMono§6poly: §cDu musst hier Keine Miete bezahlen, da auf der Strasse eine Hypothek ist.");
					return;
				}
				if($this->plugin->isFullStreet($player, $feld) == "yes"){
					if($feld == 13 or $feld == 29){
						$kosten = $points * 200;
					}else{
				        $kosten = $config->getNested($feld.".miete") * 2;
					}
				}elseif($this->plugin->isFullStreet($player, $feld) == "no"){
					$points = $gamecfg->get("lastpoints");
					if($feld == 13 or $feld == 29){
						$kosten = $points * 80;
					}elseif($feld == 6 or $feld == 16 or $feld == 26 or $feld == 36){
					    if($this->plugin->getTrainCount($player, $feld) == 1){
					        $kosten = $config->getNested($feld.".miete");
				        }elseif($this->plugin->getTrainCount($player, $feld) == 2){
					        $kosten = $config->getNested($feld.".miete") * 2;
				        }elseif($this->plugin->getTrainCount($player, $feld) == 3){
					        $kosten = $config->getNested($feld.".miete") * 2 * 2;
				        }elseif($this->plugin->getTrainCount($player, $feld) == 4){
					        $kosten = $config->getNested($feld.".miete") * 2 * 2 * 2;
						}
					}else{
						$kosten = $config->getNested($feld.".miete");
					}
				    if($playerMoney >= $kosten){
					    if($gamecfg->get("miete") == true){
					        EconomyAPI::getInstance()->reduceMoney($p, $kosten);
					        EconomyAPI::getInstance()->addMoney($player, $kosten);
					        $gamecfg->set("miete", false);
					        $gamecfg->save();
						    $p->sendMessage("§bMono§6poly: §aDu hast Miete in höhe von §d".$kosten."§a$ an §d".$player->getName()." §agezahlt.");
					    }else{
						    $p->sendMessage("§bMono§6poly: §cDu hast schon Miete bezahlt oder musst hier keine Miete bezahlen!");
					    }
				    }else{
					    $p->sendMessage("§bMono§6poly: §cDu hast nicht genug Geld um die Miete zu bezahlen nimm eine Hypotek auf oder verkauf etwas!");
				    }
				}
            }
        }
		if(!$p->isOP()){
            $ev->setCancelled(true);
		}
	}
}