<?php

declare(strict_types=1);

namespace pixelwhiz\singleplayersleep;

use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\scheduler\Task;

use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
	
    public const TIME_DAY = 1000;
    public const TIME_NOON = 6000;
    public const TIME_SUNSET = 12000;
    public const TIME_NIGHT = 13000;
    public const TIME_MIDNIGHT = 18000;
    public const TIME_SUNRISE = 23000;

    public const TIME_FULL = 24000;

    public static Main $instance;

	
    public function onEnable() : void {
        self::$instance = $this;
    }

    public static function getInstance() : Main {
        return self::$instance;
    }
	
    public function onEnterBed(PlayerBedEnterEvent $event) {
        $player = $event->getPlayer();
        $this->getScheduler()->scheduleDelayedTask(new SleepTask ($player), 20 * 5);
    }

}

class SleepTask extends Task {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(): void
    {
        $player = Main::getInstance()->getServer()->getPlayerExact($this->player->getName());
        if($player->isSleeping()){
            $player->getWorld()->setTime(Main::TIME_SUNRISE);
            $player->stopSleep();
        }
    }

}
