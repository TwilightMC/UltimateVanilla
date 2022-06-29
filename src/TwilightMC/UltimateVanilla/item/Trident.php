<?php

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla\item;

use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\ItemUseResult;
use pocketmine\item\Releasable;
use pocketmine\item\Tool;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\sound\ThrowSound;
use TwilightMC\UltimateVanilla\entity\projectile\Trident as TridentEntity;
use pocketmine\player\Player;
use function min;

class Trident extends Tool implements Releasable{
	public const TAG_TRIDENT = "Trident";

	public function getMaxDurability() : int{
		return 251;
	}

	/**
	 * The difference is between 0.07 and 0.15 if we calculate the formula based on the player's point of view.
	 *
	 * However, the exact intermediate angle is 0.15, so we specify the force as 0.15.
	 */
	public function getThrowForce() : float{
		return 0.15;
	}

	public function canStartUsingItem(Player $player) : bool{
		return true;
	}

	public function onReleaseUsing(Player $player) : ItemUseResult{
		$location = $player->getLocation();
		$diff = $player->getItemUseDuration();
		$p = $diff / 10;
		$force = min((($p ** 2) + $p * 2) / 3, 1) * 2;
		if($force < 0.15 || $diff < 2){
			return ItemUseResult::FAIL();
		}

		$projectile = new TridentEntity(Location::fromObject(
				$player->getEyePos(),
				$player->getWorld(),
				($location->yaw > 180 ? 360 : 0) - $location->yaw,
				-$location->pitch
		), $player, CompoundTag::create()->setTag(self::TAG_TRIDENT, $this->nbtSerialize()));
		$projectile->setMotion($player->getDirectionVector()->multiply($force));

		$projectileEv = new ProjectileLaunchEvent($projectile);
		if($projectileEv->isCancelled()){
			$projectile->flagForDespawn();
			return ItemUseResult::FAIL();
		}

		$projectile->spawnToAll();

		if($player->hasFiniteResources()){
			$this->pop();
		}
		$player->getWorld()->addSound($location, new ThrowSound());
		return ItemUseResult::SUCCESS();
	}

	public function onAttackEntity(Entity $victim) : bool{
		return $this->applyDamage(1);
	}

	public function getAttackPoints() : int{
		return 8;
	}
}