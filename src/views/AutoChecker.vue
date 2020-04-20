<template>
	<div class="auto-checker">
		<div class="content-center">
			<br>
			<div class="filters" style="max-width: 800px">
				<div class="filter">
					<div class="general">Domain:</div>
					<rich-sel :items="getItems()" v-model="app.query.domain" @change="refreshQuery"></rich-sel>
				</div>
				<div class="filter">
					<div class="general">Status:</div>
					<rich-sel :items="getStatuses()" v-model="app.query.st" search-hidden="1" @change="refreshQuery"></rich-sel>
				</div>
				<div class="filter">
					<btn size="small" @click="showDomainCheckPopup()">
						<i slot="icon" class="fas fa-fw fa-plus"></i>
						Add domain check
					</btn>
				</div>
				<div class="filter">
					<btn size="small" @click="urlPopup = {}" :disabled="!app.query.domain">
						<i slot="icon" class="fas fa-fw fa-plus"></i>
						Add url check
					</btn>
				</div>
			</div>
			<br>
			<br>
			<div class="tbl-outer">
				<table class="general-tbl hover-tbl">
					<thead>
					<th>#</th>
					<th>Domain</th>
					<th>Last check</th>
					<th>Http st.</th>
					<th>Noindex</th>
					<th>Robots</th>
					<th>Disallow</th>
					<th>Sitemap</th>
					<th>GA</th>
					<th>Action</th>
					</thead>
					<tbody>
					<tr>
						<td>1</td>
						<td>example.com</td>
						<td>20-12-2019</td>
						<td>1</td>
						<td>1</td>
						<td>0</td>
						<td>1</td>
						<td>0</td>
						<td>1</td>
						<td>
							<i class="link fas fa-fw fa-plus"></i>
						</td>
					</tr>
					<tr>
						<td>2</td>
						<td>test.com</td>
						<td>25-12-2019</td>
						<td>1</td>
						<td>1</td>
						<td>0</td>
						<td>1</td>
						<td>0</td>
						<td>1</td>
						<td>
							<i class="link fas fa-fw fa-plus"></i>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>

		<popup
			v-show="domainPopup"
			cls="domain-popup"
			width="400"
			@close="domainPopup = false"
			no-scroll="1"
		>
			<div slot="header">Domain check</div>
			<div>
				<div>
					<div class="general">Types:</div>
					<multi-sel :items="getDomainTypes()" v-model="domainPopup.types" class="width100"></multi-sel>
				</div>
				<template v-if="domainPopup.types">
					<div>
						<div class="general">Url of the main mirror:</div>
						<input class="width100">
					</div>
					<div v-if="domainPopup.types.indexOf('httpSt') !== -1">
						<div class="general">HTTP st:</div>
						<rich-sel :items="getHttpSt()" class="width100" search-hidden="1"></rich-sel>
					</div>
					<div v-if="domainPopup.types.indexOf('ga') !== -1">
						<div class="general">GA:</div>
						<input class="width100" placeholder="UA-XXXXXXXX-X">
					</div>
					<div v-if="domainPopup.types.indexOf('robots') !== -1">
						<div class="general">Robots:</div>
						<input class="width100">
					</div>
					<div v-if="domainPopup.types.indexOf('sitemap') !== -1">
						<div class="general">Sitemap:</div>
						<textarea class="width100"></textarea>
					</div>
				</template>
			</div>
			<div slot="footer">
				<div class="clearfix">
					<btn
						class="penalty-save right"
						size="small"
					>
						<i slot="icon" class="fas fa-fw fa-check"></i>
						Save
					</btn>
				</div>
			</div>
		</popup>

		<popup
			v-show="urlPopup"
			cls="url-popup"
			width="400"
			@close="urlPopup = false"
			no-scroll="1"
		>
			<div slot="header">Url check</div>
			<div>
				<div>
					<div class="general">Types:</div>
					<multi-sel :items="getUrlTypes()" v-model="urlPopup.types" class="width100"></multi-sel>
				</div>
				<template v-if="urlPopup.types">
					<div>
						<div class="general">Url:</div>
						<input class="width100">
					</div>
					<div>
						<div class="general">Renewal:</div>
						<rich-sel :items="getRenewals()" class="width100" search-hidden="1"></rich-sel>
					</div>
					<div v-if="urlPopup.types.indexOf('httpSt') !== -1">
						<div class="general">HTTP st:</div>
						<rich-sel :items="getHttpSt()" class="width100" search-hidden="1"></rich-sel>
					</div>
					<div v-if="urlPopup.types.indexOf('noindex') !== -1">
						<div class="general">No index:</div>
						<rich-sel :items="getNoindexSt()" class="width100" search-hidden="1"></rich-sel>
					</div>
					<div v-if="urlPopup.types.indexOf('title') !== -1">
						<div class="general">Title:</div>
						<textarea class="width100"></textarea>
					</div>
					<div v-if="urlPopup.types.indexOf('description') !== -1">
						<div class="general">Description:</div>
						<textarea class="width100"></textarea>
					</div>
					<div v-if="urlPopup.types.indexOf('tags') !== -1">
						<div class="general">Tags:</div>
						<textarea class="width100"></textarea>
					</div>
				</template>
			</div>
			<div slot="footer">
				<div class="clearfix">
					<btn
						class="penalty-save right"
						size="small"
					>
						<i slot="icon" class="fas fa-fw fa-check"></i>
						Save
					</btn>
				</div>
			</div>
		</popup>
	</div>
</template>

<style scoped>
	.popup-content textarea {
		min-width: 100%;
		max-width: 100%;
	}

	.popup-content > div > div {
		margin-bottom: 5px;
	}

	.popup-content > div > div > div {
		margin-bottom: 5px;
	}

	.popup-content > div > .clearfix > div {
		width: 49%;
	}
</style>

<script>
	import Popup from "../components/Popup";
	import RichSel from "../components/RichSel";
	import MultiSel from "../components/MultiSel";
	import Btn from "../components/Btn";

	export default {
		name: 'autoChecker',
		components: {Popup, RichSel, MultiSel, Btn},
		data() {
			return {
				domainPopup: false,
				urlPopup: false
			}
		},
		methods: {
			getDomainTypes() {
				return [
					{key: 'httpSt', val: 'Http st.'},
					{key: 'noindex', val: 'Noindex'},
					{key: 'robots', val: 'Robots.txt'},
					{key: 'disallow', val: 'Disallow: /'},
					{key: 'sitemap', val: 'Sitemap'},
					{key: 'ga', val: 'Google Analytics'},
				]
			},
			getUrlTypes() {
				return [
					{key: 'httpSt', val: 'Http st.'},
					{key: 'noindex', val: 'Noindex'},
					{key: 'title', val: 'Title'},
					{key: 'description', val: 'Description'},
					{key: 'tags', val: 'Tags'},
				]
			},
			getNoindexSt() {
				return [
					{key: 0, val: 'No noindex'},
					{key: 1, val: 'Yes noindex'},
				]
			},
			getHttpSt() {
				return [
					{key: 200, val: 200},
					{key: 301, val: 301},
					{key: 404, val: 404},
				]
			},
			getRenewals() {
				return [
					{key: 1, val: 'Everyday'},
					{key: 7, val: 'Once a week'},
					{key: 30, val: 'Once a month'},
				]
			},
			getItems() {
				return [
					{key: 123, val: 123},
					{key: 456, val: 456},
					{key: 789, val: 789},
				]
			},
			getStatuses() {
				return [
					{key: 1, val: 'Good'},
					{key: 2, val: 'Bad'},
				]
			},
			showDomainCheckPopup() {
				this.domainPopup = {
					types: 'httpSt,noindex,robots,disallow,sitemap,ga',
					domain: this.app.query.domain || ''
				};
			}
		},
	}
</script>