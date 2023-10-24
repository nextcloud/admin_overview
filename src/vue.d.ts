/**
 * SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

declare module '*.svg?raw' {
	const content: string
	export default content
}

declare module '@nextcloud/vue/dist/Components/*.js' {
	import type { Component } from 'vue'
	const component: Component
	export default component
}
