<?php

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use TwilightMC\UltimateVanilla\entity\EntityManager;
use TwilightMC\UltimateVanilla\item\ItemManager;

final class Loader extends PluginBase{
	use SingletonTrait;

	private ItemManager $itemManager;

	private EntityManager $entityManager;

	protected function onLoad() : void{
		self::$instance = $this;
		$this->itemManager = new ItemManager();
		$this->entityManager = new EntityManager();
	}
}