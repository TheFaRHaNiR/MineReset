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
declare(strict_types=1);

namespace ApexGaming\MineReset\libs\CortexPE\Commando;

use ApexGaming\MineReset\libs\CortexPE\Commando\constraint\BaseConstraint;
use ApexGaming\MineReset\libs\CortexPE\Commando\traits\ArgumentableTrait;
use ApexGaming\MineReset\libs\CortexPE\Commando\traits\IArgumentable;
use pocketmine\command\CommandSender;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\Plugin;

abstract class BaseSubCommand implements IArgumentable, IRunnable{
	use ArgumentableTrait;

	/** @var string $name*/
	private string $name;
	/** @var string[] $aliases*/
	private array $aliases;
	/** @var string $description */
	private string $description;
	/** @var string $usageMessage */
	protected string $usageMessage;
	/** @var string[] $permissions */
	private array $permissions = [];
	/** @var CommandSender $currentSender */
	protected CommandSender $currentSender;
	/** @var BaseCommand $parent */
	protected BaseCommand $parent;
	/** @var BaseConstraint[] $constraints */
	private array $constraints = [];

	public function __construct(string $name, string $description = "", array $aliases = []){
		$this->name = $name;
		$this->description = $description;
		$this->aliases = $aliases;

		$this->prepare();

		$this->usageMessage = $this->generateUsageMessage();
	}

	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	abstract public function onRun(CommandSender $sender, string $aliasUsed, array $args): void;

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function getAliases(): array{
		return $this->aliases;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getUsageMessage(): string{
		return $this->usageMessage;
	}

	/**
	 * @return string[]
	 */
	public function getPermissions(): array{
		return $this->permissions;
	}

	/**
	 * @param array $permissions
	 * @return void
	 */
	public function setPermissions(array $permissions): void{
		$permissionManager = PermissionManager::getInstance();
		foreach($permissions as $perm){
			if($permissionManager->getPermission($perm) === null){
				throw new \InvalidArgumentException("Cannot use non-existing permission \"$perm\"");
			}
		}
		$this->permissions = $permissions;
	}

	/**
	 * @param string $permission
	 * @return void
	 */
	public function setPermission(string $permission): void{
		$permissionManager = PermissionManager::getInstance();
		if($permissionManager->getPermission($permission) === null){
			throw new \InvalidArgumentException("Cannot use non-existing permission \"$permission\"");
		}
		$this->permissions[] = $permission;
	}

	/**
	 * @param CommandSender $sender
	 * @return boolean
	 */
	public function testPermissionSilent(CommandSender $sender): bool{
		foreach($this->permissions as $permission){
			if($sender->hasPermission($permission)){
				return true;
			}
		}

		return false;
	}

	/**
	 * @param CommandSender $currentSender
	 *
	 * @internal Used to pass the current sender from the parent command
	 */
	public function setCurrentSender(CommandSender $currentSender): void{
		$this->currentSender = $currentSender;
	}

	/**
	 * @param BaseCommand $parent
	 *
	 * @internal Used to pass the parent context from the parent command
	 */
	public function setParent(BaseCommand $parent): void{
		$this->parent = $parent;
	}

	/**
	 * @param integer $errorCode
	 * @param array $args
	 * @return void
	 */
	public function sendError(int $errorCode, array $args = []): void{
		$this->parent->sendError($errorCode, $args);
	}

	/**
	 * @return void
	 */
	public function sendUsage(): void{
		$this->currentSender->sendMessage("/{$this->parent->getName()} $this->usageMessage");
	}

	/**
	 * @param BaseConstraint $constraint
	 * @return void
	 */
    public function addConstraint(BaseConstraint $constraint): void{
        $this->constraints[] = $constraint;
    }

    /**
     * @return BaseConstraint[]
     */
    public function getConstraints(): array{
        return $this->constraints;
    }

	/**
	 * @return Plugin
	 */
	public function getOwningPlugin(): Plugin{
		return $this->parent->getOwningPlugin();
	}
}