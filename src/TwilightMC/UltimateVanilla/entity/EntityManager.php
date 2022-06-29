<?php

/**
 * @noinspection PhpUndefinedMethodInspection
 */

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla\entity;

use pocketmine\entity\EntityDataHelper as Helper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;
use TwilightMC\UltimateVanilla\entity\projectile\Trident;

final class EntityManager{

	public function __construct(){
		(function(): void{
			$this->register(Trident::class, function(World $world, CompoundTag $nbt): Trident{
				return new Trident(Helper::parseLocation($nbt, $world), null, $nbt);
			}, ['Trident', EntityIds::THROWN_TRIDENT, 'minecraft:trident']);
		})->call(EntityFactory::getInstance());
	}
}