<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

/**
 * @template-extends QBMapper<ClientDiagnostic>
 */
class ClientDiagnosticMapper extends QBMapper {
	public const TABLE_NAME = 'client_diagnostics';

	public function __construct(IDBConnection $db) {
		parent::__construct($db, self::TABLE_NAME, ClientDiagnostic::class);
	}

	/**
	 * @return ClientDiagnostic[]
	 */
	public function getAll(): array {
		$qb = $this->db->getQueryBuilder();

		$select = $qb
			->select('*')
			->from($this->getTableName());

		return $this->findEntities($select);
	}

	/**
	 * @throws DoesNotExistException
	 */
	public function findByAuthtokenid(int $id): ClientDiagnostic {
		$qb = $this->db->getQueryBuilder();

		$select = $qb
			->select('*')
			->from($this->getTableName())
			->where($qb->expr()->eq('authtokenid', $qb->createNamedParameter($id, $qb::PARAM_INT)));

		return $this->findEntity($select);
	}
}
