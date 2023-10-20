<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-FileCopyrightText: 2023 Ferdinand Thiessen <opensource@fthiessen.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\AppInfo;

use OCA\AdminOverview\Capabilities;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
	public const APP_ID = 'admin_overview';

	public function __construct(array $params = []) {
		parent::__construct(self::APP_ID, $params);
	}

	public function register(IRegistrationContext $context): void {
		// Register the composer autoloader for packages shipped by this app, if applicable
		include_once __DIR__ . '/../../vendor/autoload.php';

		/*
		 * Register capabilities
		 */
		$context->registerCapability(Capabilities::class);
	}

	/**
	 * Boot Logic
	 * @param IBootContext $context
	 */
	public function boot(IBootContext $context): void {
		// No boot logic here yet...
	}
}
