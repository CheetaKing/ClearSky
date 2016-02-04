<?php
namespace pocketmine\entity;

use pocketmine\Player;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\protocol\AddPaintingPacket;

class Painting extends Hanging{
	const NETWORK_ID = 83;

	private $motive;
	
	public function initEntity(){
		$this->setMaxHealth(1);
		parent::initEntity();
		
		if(isset($this->namedtag->Motive)) {
			$this->motive = $this->namedtag["Motive"];
		} else $this->close();
	}
	
	public function spawnTo(Player $player){
		$pk = new AddPaintingPacket();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->direction = $this->getDirection();
		$pk->title = $this->motive;
		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

	public function kill(){
		parent::kill();
	
		foreach($this->getDrops() as $item){
			$this->getLevel()->dropItem($this, $item);
		}
	}
	
	public function getDrops(){
		return [ItemItem::get(ItemItem::PAINTING, 0, 1)];
	}
}
