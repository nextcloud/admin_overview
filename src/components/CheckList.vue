<!--
	SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
	SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div :class="$style.wrapper">
		<h3 :class="$style.headline">
			{{ category }}
		</h3>
		<ul :class="$style.list">
			<CheckEntry v-for="entry, name of setupChecks"
				:key="name"
				:name="name"
				:description="entry.description"
				:severity="entry.severity" />
		</ul>
	</div>
</template>

<script setup lang="ts">
import type { ISetupCheckResponse } from '../types'
import CheckEntry from './CheckEntry.vue'

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps<{
	category: string
	setupChecks: ISetupCheckResponse
}>()

</script>

<style module lang="scss">
// The list content
.list {
	display: flex;
	flex-direction: column;
	gap:12px;
	justify-content: start;
}

// Outer wrapper around a category
.wrapper {
	display: flex;
	flex-direction: column;
	gap: 12px;
}

// The list headline / name
.headline {
	font-size: 18px;
	font-weight: bold;
	line-height: 1.5;

	padding-inline-start: 12px;
}
</style>
