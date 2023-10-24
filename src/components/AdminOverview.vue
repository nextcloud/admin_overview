<!--
	SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
	SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<NcSettingsSection :name="t('admin_overview', 'Admin overview')">
		<div :class="$style.wrapper">
			<div :class="$style.container">
				<CheckList v-for="category in categories"
					:key="category"
					:category="category"
					:setup-checks="checks[category]" />
			</div>
		</div>
	</NcSettingsSection>
</template>

<script setup lang="ts">
import type { ICategorizedSetupChecks } from '../types'
import { translate as t } from '@nextcloud/l10n'
import { loadState } from '@nextcloud/initial-state'
import { computed } from 'vue'

import CheckList from './CheckList.vue'
import NcSettingsSection from '@nextcloud/vue/dist/Components/NcSettingsSection.js'

const checks = loadState<ICategorizedSetupChecks>(appName, 'setupChecks')

/**
 * List of category names
 */
const categories = computed(() => Object.keys(checks))
</script>

<style module lang="scss">
// The inner container for each list of startup checks
.container {
	display: flex;
	flex-direction: row;
	gap: 22px;
	justify-content: space-around;
	width: fit-content;
}

// The outer wrapper providing the scrollbar
.wrapper {
	display: flex;
	overflow-x: scroll;

	width: 100%;
}
</style>
