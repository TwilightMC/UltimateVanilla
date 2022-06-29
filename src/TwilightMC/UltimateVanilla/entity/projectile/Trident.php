<?php

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla\entity\projectile;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\projectile\Projectile;
use pocketmine\math\RayTraceResult;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemActorPacket;
use TwilightMC\UltimateVanilla\item\Trident as TridentItem;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Trident extends Projectile{

	protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(0.25, 0.25); }

	public static function getNetworkTypeId() : string{ return EntityIds::THROWN_TRIDENT; }

	protected $gravity = 0.03;
	protected $drag = 0.01;
	protected $damage = 8.0;

	protected int $age = 0;

	private Item $item;

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);
		$tridentTag = $nbt->getTag(TridentItem::TAG_TRIDENT);
		if(!$tridentTag instanceof CompoundTag){
			$this->close();
			return;
		}
		$this->item = Item::nbtDeserialize($tridentTag);
	}

	public function saveNBT() : CompoundTag{
		return parent::saveNBT()->setTag(TridentItem::TAG_TRIDENT, $this->item->nbtSerialize());
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		if($this->closed){
			return false;
		}

		$hasUpdate = parent::entityBaseTick($tickDiff);
		if(++$this->age > 1200){
			$this->flagForDespawn();
			$hasUpdate = true;
		}
		return $hasUpdate;
	}

	public function onCollideWithPlayer(Player $player) : void{
		if($this->blockHit === null){
			return;
		}

		$owner = $this->getOwningEntity();
		if(!$owner instanceof Player || ($owner->getId() !== $player->getId() || !$player->getInventory()->canAddItem($this->item))){
			return;
		}

		$this->getWorld()->broadcastPacketToViewers($this->getPosition(), TakeItemActorPacket::create($player->getId(), $this->getId()));

		if($player->hasFiniteResources()){
			$player->getInventory()->addItem(clone $this->item);
		}
		$this->flagForDespawn();
	}

	protected function onHitEntity(Entity $entityHit, RayTraceResult $hitResult) : void{
		parent::onHitEntity($entityHit, $hitResult);
		$this->getWorld()->broadcastPacketToViewers($this->getPosition(), PlaySoundPacket::create(
			soundName: 'item.trident.hit',
			x: $this->getPosition()->x,
			y: $this->getPosition()->y,
			z: $this->getPosition()->z,
			volume: 1,
			pitch: 1));
	}

	protected function onHitBlock(Block $blockHit, RayTraceResult $hitResult) : void{
		parent::onHitBlock($blockHit, $hitResult);
		$this->getWorld()->broadcastPacketToViewers($this->getPosition(), PlaySoundPacket::create(
			soundName: 'item.trident.hit_ground',
			x: $this->getPosition()->x,
			y: $this->getPosition()->y,
			z: $this->getPosition()->z,
			volume: 1,
			pitch: 1));
	}
}