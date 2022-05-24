<?php

namespace Monopoly;

use pocketmine\event\{
	Listener,
	block\BlockPlaceEvent,
	block\BlockBreakEvent,
	player\PlayerLoginEvent,
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
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Monopoly\Main;

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
        $bauen->setCustomName("§bBauen");       
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
	
	public function Hunger(PlayerExhaustEvent $event){
        $event->setCancelled(true);
    }
	
	
    public function onFallDamage(EntityDamageEvent $event){
        $event->setCancelled(true);
    }
  
    public function onInteract(PlayerInteractEvent $ev){
        $p = $ev->getPlayer();
        $item = $ev->getItem();
		if($item->getId() === 236) {
            if($item->getName() === "§aWürfeln") {
                $wurf = Main::getInstance()->getZufall1() + Main::getInstance()->getZufall2();
				$isPasch = Main::getInstance()->isPasch();
            }
        }
		if($item->getId() === 266) {
            if($item->getName() === "§6Kaufen") {
                    
            }
        }
		if($item->getId() === 277) {
            if($item->getName() === "§bBauen") {
                    
            }
        }
		if($item->getId() === 46) {
            if($item->getName() === "§eHypothek") {
                    
            }
        }
		if($item->getId() === 54) {
            if($item->getName() === "§dHandeln") {
                    
            }
        }
		if($item->getId() === 340) {
            if($item->getName() === "§7Infos") {
                    
            }
        }
		if($item->getId() === 355) {
            if($item->getName() === "§cAufgeben/Bankrott") {
                    
            }
        }
	}
}
