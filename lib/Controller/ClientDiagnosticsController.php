<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\Controller;

use OCA\AdminOverview\Db\ClientDiagnostic;
use OCA\AdminOverview\Db\ClientDiagnosticMapper;
use OCA\AdminOverview\ResponseDefinitions;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\Authentication\Exceptions\InvalidTokenException;
use OCP\Authentication\Token\IProvider;
use OCP\IRequest;
use OCP\ISession;
use OCP\IUserSession;
use OCP\Session\Exceptions\SessionNotAvailableException;
use Psr\Clock\ClockInterface;

/**
 * @psalm-import-type AdminOverviewProblems from ResponseDefinitions
 */
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

	/**
	 * @param AdminOverviewProblems $problems Problems to report for this client
	 * @return DataResponse<Http::STATUS_OK, array<empty>, array{}>|DataResponse<Http::STATUS_METHOD_NOT_ALLOWED, array<empty>, array{}>|DataResponse<Http::STATUS_INTERNAL_SERVER_ERROR, array{message: string}, array{}>
	 *
	 * Update a client diagnostic listing problems encountered by the client
	 *
	 * 200: Diagnostic was correctly updated
	 * 405: Token is invalid or user is impersonated
	 * 500: Session was not correctly setup
	 */
	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function update(array $problems): DataResponse {
		try {
			$sessionId = $this->session->getId();
		} catch (SessionNotAvailableException $e) {
			return new DataResponse(['message' => $e->getMessage()], Http::STATUS_INTERNAL_SERVER_ERROR);
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
