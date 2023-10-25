/**
 * SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

export type ISeverity = 'success' | 'info' | 'warning' | 'error'

export interface ISetupCheck {
	severity: ISeverity
	description: string | null
	linkToDoc: string | null
}

/**
 * Mapping name => ISetupCheck
 */
export type ISetupCheckResponse = Record<string, ISetupCheck>

/**
 * Categorized setup checks, { category_name: setupchecks }
 */
export type ICategorizedSetupChecks = Record<string, ISetupCheckResponse>
