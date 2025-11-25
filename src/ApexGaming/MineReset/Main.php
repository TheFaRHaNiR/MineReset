<?php

declare(strict_types=1);

namespace ApexGaming\MineReset;

use pocketmine\plugin\PluginBase;
use ApexGaming\MineReset\libs\SOFe\AwaitStd\AwaitStd;
use ApexGaming\MineReset\libs\CortexPE\Commando\PacketHooker;
use ApexGaming\MineReset\commands\MineCommand;
use ApexGaming\MineReset\mine\MineRegistry;

class Main extends PluginBase{

	/** @var self $instance */
	private static self $instance;

	/** @var AwaitStd $std */
	private AwaitStd $std;

	public static $blockReplaceTick;
	# Check new version of config
	private const CONFIG_VERSION = "1.0.0";

	/**
	 * @return void
	 */
	protected function onLoad(): void{
		self::$instance = $this;
		$this->std = AwaitStd::init($this);
		MineRegistry::getInstance();
		$this->loadFiles();
	}

	/**
	 * @return void
	 */
	protected function onEnable(): void{
		$this->loadCheck();
		if(!PacketHooker::isRegistered()){
			PacketHooker::register($this);
		}
		$this->getServer()->getCommandMap()->register("minereset", new MineCommand);
	}

	/**
	 * @return void
	 */
	protected function onDisable(): void{
		MineRegistry::getInstance()->onClose($this);
	}

	/**
	 * @return void
	 */
	private function loadFiles(): void{
		$this->saveResource("config.yml");
		self::$blockReplaceTick = $this->getConfig()->get("blockReplaceTick", 1000);
	}

	/**
	 * @return void
	 */
	private function loadCheck(): void{
		if((!$this->getConfig()->exists("config-version")) || ($this->getConfig()->get("config-version") != self::CONFIG_VERSION)){
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->critical("Your configuration file is outdated.");
            $this->getLogger()->notice("Your old configuration has been saved as config_old.yml and a new configuration file has been generated. Please update accordingly.");
        }
	}

	/**
	 * @return Main
	 */
	public static function getInstance(): Main{
		return self::$instance;
	}

	/**
	 * @return AwaitStd
	 */
	public function getStd(): AwaitStd{
		return $this->std;
	}

	/**
	 * @return string
	 */
	public static function getPrefix(): string{
		return self::$instance->getConfig()->get("prefix");
	}
}
