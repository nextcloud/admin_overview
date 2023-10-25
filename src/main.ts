/**
 * SPDX-FileCopyrightText: Ferdinand Thiessen <opensource@fthiessen.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import Vue from 'vue'
import AdminOverview from './components/AdminOverview.vue'

export default new Vue({
	el: '#admin-overview',
	name: 'AdminOverviewRoot',
	render(h) {
		return h(AdminOverview)
	},
})
