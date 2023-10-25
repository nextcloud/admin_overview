<!--
	SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
	SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<li :class="$style.wrapper">
		<NcIconSvgWrapper :class="iconWrapperClass" :path="svgPath" />
		<div>
			<div :class="$style.title">
				{{ name }}
			</div>
			<div :class="$style.description">
				{{ description }}
			</div>
		</div>
	</li>
</template>

<script setup lang="ts">
import NcIconSvgWrapper from '@nextcloud/vue/dist/Components/NcIconSvgWrapper.js'
import {
	mdiAlert,
	mdiCheck,
	mdiExclamationThick,
	mdiHelp,
	mdiInformation,
} from '@mdi/js'
import { computed, useCssModule } from 'vue'

// see types.ts (Vue 3 will allow to import the type and use as prop)
const props = defineProps<{
	name: string
	description: string | null
	severity: 'success' | 'info' | 'warning' | 'error'
}>()

/**
 * CSS classes (as we use CSS modules this is the mapping from readable names to the compiled class names)
 */
const style = useCssModule()

/**
 * MDI Icon to show for the level
 */
const svgPath = computed(() => {
	switch (props.severity) {
	case 'success':
		return mdiCheck
	case 'info':
		return mdiInformation
	case 'warning':
		return mdiAlert
	case 'error':
		return mdiExclamationThick
	default:
		return mdiHelp
	}
})

/**
 * Class to assign to the icon wrapper
 * Used to set the background color
 */
const iconWrapperClass = computed(() => style[props.severity])
</script>

<style module lang="scss">
.wrapper {
	display: flex;
	flex-direction: row;
	gap: 12px;

	min-width: 240px;
}

.title {
	font-weight: bold;
}

.description {
	color: var(--color-text-maxcontrast);
}

.servity-icon {
	// Make the icons round
	border-radius: 50%;
	// TODO: Allow size parameter in NcIconSvgWrapper
	max-height: 50px;
	max-width: 50px;
	min-width: 50px !important;
	min-height: 50px !important;
}

.success {
	composes: servity-icon;
	background-color: var(--color-success);
}

.info {
	composes: servity-icon;
	background-color: var(--color-info);
}

.warning {
	composes: servity-icon;
	background-color: var(--color-warning);
}

.error {
	composes: servity-icon;
	background-color: var(--color-error);
}
</style>
