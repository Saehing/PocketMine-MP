<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\raklib;

use pocketmine\network\mcpe\PacketSender;

class RakLibPacketSender implements PacketSender{

	/** @var int */
	private $sessionId;
	/** @var RakLibInterface */
	private $handler;

	/** @var bool */
	private $closed = false;

	public function __construct(int $sessionId, RakLibInterface $handler){
		$this->sessionId = $sessionId;
		$this->handler = $handler;
	}

	public function send(string $payload, bool $immediate) : void{
		if(!$this->closed){
			$this->handler->putPacket($this->sessionId, $payload, $immediate);
		}
	}

	public function close(string $reason = "unknown reason") : void{
		if(!$this->closed){
			$this->closed = true;
			$this->handler->close($this->sessionId, $reason);
		}
	}
}
