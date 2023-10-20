<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Côme Chilliet <come.chilliet@nextcloud.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\AdminOverview;

/**
 * @psalm-type AdminOverviewProblems = array{
 * 	conflict:array{
 * 		count:int,
 * 		oldest:int
 * 	},
 * 	failed-upload:array{
 * 		count:int,
 * 		oldest:int
 * 	}
 * }
 */
class ResponseDefinitions {
}
