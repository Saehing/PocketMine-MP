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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\entity\Attribute;
use pocketmine\network\mcpe\handler\SessionHandler;
use pocketmine\network\mcpe\NetworkBinaryStream;
use function array_values;

class UpdateAttributesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_ATTRIBUTES_PACKET;

	/** @var int */
	public $entityRuntimeId;
	/** @var Attribute[] */
	public $entries = [];

	/**
	 * @param int         $entityRuntimeId
	 * @param Attribute[] $attributes
	 *
	 * @return UpdateAttributesPacket
	 */
	public static function create(int $entityRuntimeId, array $attributes) : self{
		(function(Attribute ...$attributes){})(...$attributes);
		$result = new self;
		$result->entityRuntimeId = $entityRuntimeId;
		$result->entries = $attributes;
		return $result;
	}

	protected function decodePayload(NetworkBinaryStream $in) : void{
		$this->entityRuntimeId = $in->getEntityRuntimeId();
		$this->entries = $in->getAttributeList();
	}

	protected function encodePayload(NetworkBinaryStream $out) : void{
		$out->putEntityRuntimeId($this->entityRuntimeId);
		$out->putAttributeList(...array_values($this->entries));
	}

	public function handle(SessionHandler $handler) : bool{
		return $handler->handleUpdateAttributes($this);
	}
}
