<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\AdminOverview\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\ICache;
use OCP\IL10N;
use OCP\Settings\IDelegatedSettings;
use OCP\SetupCheck\ISetupCheckManager;
use OCP\Util;

class Admin implements IDelegatedSettings {

	public function __construct(
		protected string $appName,
		private IL10N $l10n,
		private IInitialState $initialState,
		private ISetupCheckManager $manager,
		private ICache $cache,
	) {
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		$checks = $this->cache->get('setupChecks');
		if ($checks !== null) {
			$checks = json_decode($checks, true);
		} else {
			// TODO: Move this to API for faster loading
			$checks = $this->manager->runAll();
			$this->cache->set('setupChecks', json_encode($checks));
		}
		$this->initialState->provideInitialState('setupChecks', $checks);

		Util::addScript($this->appName, $this->appName . '-main');
		Util::addStyle($this->appName, $this->appName . '-main');

		return new TemplateResponse($this->appName, 'settings-admin');
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection(): string {
		return 'overview';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority(): int {
		return 0;
	}

	public function getName(): ?string {
		return $this->l10n->t('Admin overview');
	}

	public function getAuthorizedAppConfig(): array {
		return [];
	}
}
