<?php

/**
 * @noinspection PhpUndefinedMethodInspection
 */

declare(strict_types=1);

namespace TwilightMC\UltimateVanilla\item;

use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\item\ItemIds;
use TwilightMC\UltimateVanilla\item\projectile\IceBomb;
use TwilightMC\UltimateVanilla\item\projectile\Trident;

final class ItemManager{

	private function registerAllCreativeItems() : void{
		$originItems = CreativeInventory::getInstance()->getAll();

		CreativeInventory::reset();
		$inv = CreativeInventory::getInstance();
		foreach($originItems as $item){
			if(!$inv->contains($item)){
				$inv->add($item);
			}
		}
	}

	public function __construct(){
		(function(): void{
			$this->register(new Trident(new IID(ItemIds::TRIDENT, 0), 'Trident'));
			$this->register(new IceBomb(new IID(ItemIds::ICE_BOMB, 0), 'IceBomb'));
		})->call(ItemFactory::getInstance());

		$this->registerAllCreativeItems();
	}
}