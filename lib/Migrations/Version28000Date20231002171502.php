<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview\Migrations;

use Closure;
use OCA\AdminOverview\Db\ClientDiagnosticMapper;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Introduce client_diagnostics table
 */
class Version28000Date20231002171502 extends SimpleMigrationStep {
	/**
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable(ClientDiagnosticMapper::TABLE_NAME)) {
			$table = $schema->createTable(ClientDiagnosticMapper::TABLE_NAME);

			$table->addColumn('id', Types::BIGINT, [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);
			$table->addColumn('authtokenid', Types::BIGINT, [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('diagnostic', Types::TEXT, [
				'notnull' => true,
			]);
			$table->addColumn('timestamp', Types::DATETIME, [
				'notnull' => true,
			]);

			$table->setPrimaryKey(['id'], 'client_diag_id_primary');
			$table->addUniqueIndex(['authtokenid'], 'client_diag_authtokenid_index');

			$changed = true;
			return $schema;
		}

		return null;
	}
}
