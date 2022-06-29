<?php

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

final class Loader extends PluginBase{
	use SingletonTrait;

	protected function onLoad() : void{
		self::$instance = $this;
	}
}