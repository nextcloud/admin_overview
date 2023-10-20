<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method void setAuthtokenid(int $authtokenid)
 * @method int getAuthtokenid()
 * @method void setDiagnostic(string $diagnostic)
 * @method string getDiagnostic()
 * @method \DateTime getTimestamp()
 * @method void setTimestamp(\DateTime $timestamp)
 */
class ClientDiagnostic extends Entity {
	public const TYPE_CONFLICT = 'conflict';
	public const TYPE_FAILED_UPLOAD = 'failed-upload';
	public const TYPES = [
		self::TYPE_CONFLICT,
		self::TYPE_FAILED_UPLOAD,
	];

	/** @var int */
	protected $authtokenid;

	/** @var string json-encoded*/
	protected $diagnostic;

	/** @var \DateTime */
	public $timestamp;

	public function __construct() {
		$this->addType('authtokenid', 'int');
		$this->addType('diagnostic', 'string');
		$this->addType('timestamp', 'datetime');
	}

	public function getDiagnosticAsArray(): array {
		$data = json_decode($this->diagnostic, true, JSON_THROW_ON_ERROR);
		return $data;
	}
}
