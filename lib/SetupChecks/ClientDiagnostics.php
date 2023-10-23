<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\SetupChecks;

use OCA\AdminOverview\Db\ClientDiagnostic;
use OCA\AdminOverview\Db\ClientDiagnosticMapper;
use OCP\Authentication\Token\IProvider;
use OCP\Authentication\Token\IToken;
use OCP\IConfig;
use OCP\IL10N;
use OCP\SetupCheck\ISetupCheck;
use OCP\SetupCheck\SetupResult;

class ClientDiagnostics implements ISetupCheck {
	/**
	 * Maximum age for a client diagnostic to be considered still valid, in seconds
	 * Diagnostic timestamp will be compared to last check of the associated authtoken
	 */
	public const MAX_AGE = 24 * 60 * 60;

	public function __construct(
		private IConfig $config,
		private IL10N $l10n,
		private ClientDiagnosticMapper $diagnosticMapper,
		private IProvider $tokenProvider,
	) {
	}

	public function getName(): string {
		return $this->l10n->t('Sync problems');
	}

	public function getCategory(): string {
		return 'accounts';
	}

	public function run(): SetupResult {
		$diagnostics = $this->diagnosticMapper->getAll();

		$problems = [];
		foreach ($diagnostics as $diagnostic) {
			$token = $this->getAuthtoken($diagnostic);
			if (!$this->isRecentEnough($diagnostic, $token)) {
				// TODO delete diagnostic?
				continue;
			}
			$data = $diagnostic->getDiagnosticAsArray();
			foreach ($data['problems'] as $type => $problemDetails) {
				$problems[] = [
					$type,
					$problemDetails['count'],
					$token->getUID(),
					$token->getName(),
					$problemDetails['oldest']
				];
			}
		}
		/*
		 * TODO:How to format data, and how to pass it to the frontend?
		 */
		if (empty($problems)) {
			return SetupResult::success($this->l10n->t('No client reported any problem'));
		} else {
			return SetupResult::warning($this->l10n->n('%n client have sync problems', '%d clients have sync problems', count($problems)));
		}
	}

	private function getAuthtoken(ClientDiagnostic $diagnostic): IToken {
		return $this->tokenProvider->getTokenById($diagnostic->getAuthtokenid());
	}

	private function isRecentEnough(ClientDiagnostic $diagnostic, IToken $token): bool {
		return ($diagnostic->getTimestamp()->getTimestamp() >= $token->getLastCheck() + self::MAX_AGE);
	}
}
