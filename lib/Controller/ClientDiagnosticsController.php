<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\Controller;

use OC\Authentication\Exceptions\InvalidTokenException;
use OC\Authentication\Token\IProvider;
use OCA\AdminOverview\Db\ClientDiagnostic;
use OCA\AdminOverview\Db\ClientDiagnosticMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use OCP\ISession;
use OCP\IUserSession;
use OCP\Session\Exceptions\SessionNotAvailableException;
use Psr\Clock\ClockInterface;

class ClientDiagnosticsController extends OCSController {
	public function __construct(
		string $appName,
		IRequest $request,
		private ISession $session,
		private IUserSession $userSession,
		private ClientDiagnosticMapper $mapper,
		private IProvider $tokenProvider,
		private ClockInterface $clock,
	) {
		parent::__construct($appName, $request);
	}

	#[NoAdminRequired]
	#[NoSubAdminRequired]
	#[NoCSRFRequired]
	public function update(array $problems): DataResponse {
		try {
			$sessionId = $this->session->getId();
		} catch (SessionNotAvailableException $e) {
			return new DataResponse(['message' => $e->getMessage()], Http::STATUS_SERVICE_UNAVAILABLE);
		}
		if ($this->userSession->getImpersonatingUserID() !== null) {
			return new DataResponse([], Http::STATUS_METHOD_NOT_ALLOWED);
		}

		$appPassword = $this->session->get('app_password');

		try {
			$token = $this->tokenProvider->getToken($appPassword);
		} catch (InvalidTokenException $e) {
			return new DataResponse([], Http::STATUS_METHOD_NOT_ALLOWED);
		}

		/* TODO: validate problems structure */

		try {
			$entity = $this->mapper->findByAuthtokenid($token->getId());
			$entity->setDiagnostic(json_encode(['problems' => $problems], JSON_THROW_ON_ERROR));
			$entity->setTimestamp(\DateTime::createFromImmutable($this->clock->now()));
			$this->mapper->update($entity);
		} catch (DoesNotExistException $e) {
			$entity = $this->mapper->insert(
				ClientDiagnostic::fromParams([
					'authtokenid' => $token->getId(),
					'diagnostic' => json_encode(['problems' => $problems], JSON_THROW_ON_ERROR),
					'timestamp' => \DateTime::createFromImmutable($this->clock->now()),
				]));
		}

		return new DataResponse([]);
	}
}
