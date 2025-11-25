<?php

/***
 *    ___                                          _
 *   / __\___  _ __ ___  _ __ ___   __ _ _ __   __| | ___
 *  / /  / _ \| '_ ` _ \| '_ ` _ \ / _` | '_ \ / _` |/ _ \
 * / /__| (_) | | | | | | | | | | | (_| | | | | (_| | (_) |
 * \____/\___/|_| |_| |_|_| |_| |_|\__,_|_| |_|\__,_|\___/
 *
 * Commando - A Command Framework virion for PocketMine-MP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Written by @CortexPE <https://CortexPE.xyz>
 *
 */

namespace ApexGaming\MineReset\libs\CortexPE\Commando\store;

use ApexGaming\MineReset\libs\CortexPE\Commando\exception\CommandoException;
use pocketmine\network\mcpe\NetworkBroadcastUtils;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandHardEnum;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;
use pocketmine\Server;

class SoftEnumStore{

	/** @var CommandHardEnum[] $enums */
	private static array $enums = [];

	/**
	 * @param string $name
	 * @return CommandHardEnum|null
	 */
	public static function getEnumByName(string $name): ?CommandHardEnum{
		return static::$enums[$name] ?? null;
	}

	/**
	 * @return CommandHardEnum[]
	 */
	public static function getEnums(): array{
		return static::$enums;
	}

	/**
	 * @param CommandHardEnum $enum
	 * @return void
	 */
	public static function addEnum(CommandHardEnum $enum): void{
		static::$enums[$enum->getName()] = $enum;
		self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_ADD);
	}

	/**
	 * @param string $enumName
	 * @param array $values
	 * @return void
	 */
	public static function updateEnum(string $enumName, array $values): void{
		if(self::getEnumByName($enumName) === null){
			throw new CommandoException("Unknown enum named " . $enumName);
		}
		$enum = self::$enums[$enumName] = new CommandHardEnum($enumName, $values);
		self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_SET);
	}

	/**
	 * @param string $enumName
	 * @return void
	 */
	public static function removeEnum(string $enumName): void{
		if(($enum = self::getEnumByName($enumName)) === null){
			throw new CommandoException("Unknown enum named " . $enumName);
		}
		unset(static::$enums[$enumName]);
		self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_REMOVE);
	}

	/**
	 * @param CommandHardEnum $enum
	 * @param integer $type
	 * @return void
	 */
	public static function broadcastSoftEnum(CommandHardEnum $enum, int $type): void{
		$pk = new UpdateSoftEnumPacket();
		$pk->enumName = $enum->getName();
		$pk->values = $enum->getValues();
		$pk->type = $type;
		self::broadcastPacket($pk);
	}

	/**
	 * @param ClientboundPacket $pk
	 * @return void
	 */
	private static function broadcastPacket(ClientboundPacket $pk): void{
		$players = Server::getInstance()->getOnlinePlayers();
		NetworkBroadcastUtils::broadcastPackets($players, [$pk]);
	}
}