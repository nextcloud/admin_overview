<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\AdminOverview;

use OCP\Capabilities\ICapability;

class Capabilities implements ICapability {
	/**
	 * Return this classes capabilities
	 *
	 * @return array{admin_overview: array{diagnostics: bool}}
	 */
	public function getCapabilities() {
		return [
			'admin_overview' => [
				'diagnostics' => true,
			],
		];
	}
}
