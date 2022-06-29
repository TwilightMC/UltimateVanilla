<?php

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla\item;

use TwilightMC\UltimateVanilla\entity\projectile\IceBomb as IceBombEntity;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ProjectileItem;
use pocketmine\player\Player;

class IceBomb extends ProjectileItem{

	public function getThrowForce() : float{
		return 1.5;
	}

	protected function createEntity(Location $location, Player $thrower) : Throwable{
		return new IceBombEntity($location, $thrower);
	}

	public function getCooldownTicks() : int{
		return 10;
	}
}