<template>
	<div class="auto-checker" v-if="init">
		<div class="content-center">
			<div class="filters" style="max-width: 800px">
				<div class="filter">
					<div class="general">Domain:</div>
					<rich-sel items-url="/autochecks/domains-search" v-model="app.query.domain" @change="refreshQuery"></rich-sel>
				</div>
				<div class="filter">
					<div class="general">Status:</div>
					<rich-sel :items="getStatuses()" v-model="app.query.status" search-hidden="1" @change="refreshQuery"></rich-sel>
				</div>
				<div class="filter">
					<btn size="small" @click="showDomainCheckPopup()">
						<i slot="icon" class="fas fa-fw fa-plus"></i>
						Add domain check
					</btn>
				</div>
				<div class="filter">
					<btn
						size="small"
						@click="showUrlCheckPopup()"
						:disabled="!app.query.domain || !this.domainsRows || !this.domainsRows.length"
					>
						<i slot="icon" class="fas fa-fw fa-plus"></i>
						Add url check
					</btn>
				</div>
			</div>

			<template v-if="domainsRows">
				<h5 class="text-center table-name">Domains checks:</h5>
				<div class="tbl-outer">
					<div v-if="domainsTotalCnt != null">
						Total:
						<i v-if="domainsTotalCnt === -1" class="fas fa-spinner fast-spin"></i>
						<span v-else>{{ domainsTotalCnt }}</span>
					</div>
					<table class="general-tbl hover-tbl">
						<thead>
						<th>#</th>
						<th><sort sort="domain">Domain</sort></th>
						<th><sort sort="lastCheck">Last check</sort></th>
						<th v-for="c in domainChecklist">{{ c.title }}</th>
						<th>Action</th>
						</thead>
						<tbody>
						<template v-for="(row, i) in domainsRows">
							<tr>
								<td>{{ i + 1 }}</td>
								<td>
									<a :href="row.url" target="_blank" rel="noreferrer noopener">
										{{ row.domain }}
									</a>
								</td>
								<td>
									<span v-if="row.lastCheck && row.lastCheck !== '0000-00-00 00:00:00'">
										{{ row.lastCheck }}
									</span>
									<span v-else>-</span>
								</td>
								<td v-for="c in domainChecklist">
									<a
										v-if="row.statuses[c.key]"
										:title="getTitle(row, row.statuses[c.key])"
										class="help"
									>
										<i :class="'fa fas fa-' + checkStatusMap[row.statuses[c.key].status].icon"></i>
									</a>
									<span v-else>-</span>
								</td>
								<td nowrap>
									<span
										class="margin-right-10 link"
										@click="updateCheck(row, row.id, 'domains')"
									>
										<i
											class="fa-fw fas fa-sync"
											:class="[{'fast-spin': row.isUpdating}]"
										></i>
									</span>
									<span
										class="margin-right-10 link"
										@click="showDomainCheckPopup(row)"
									>
										<i class="fa-fw fas fa-edit"></i>
									</span>
									<span
										class="link"
										@click="deleteCheck(row.id, i, 'domains')"
									>
										<i class="fa-fw fas fa-times"></i>
									</span>
								</td>
							</tr>
						</template>
						</tbody>
						<tfoot>
						<tr v-if="domainsRows && domainsRows.length && isLoadingMore">
							<td colspan="100" class="text-center">
								<i class="fas fa-spinner fast-spin"></i>
								Loading...
							</td>
						</tr>
						<tr v-if="isNoMore">
							<td colspan="100" class="text-center">
								{{ domainsRows && domainsRows.length ? 'No more data' : 'No data' }}
							</td>
						</tr>
						</tfoot>
					</table>
				</div>
			</template>

			<template v-if="urlsRows">
				<h5 class="text-center table-name">Urls checks:</h5>
				<div class="tbl-outer">
					<div v-if="urlsTotalCnt != null">
						Total:
						<i v-if="urlsTotalCnt === -1" class="fas fa-spinner fast-spin"></i>
						<span v-else>{{ urlsTotalCnt }}</span>
					</div>
					<table class="general-tbl hover-tbl">
						<thead>
						<th>#</th>
						<th><sort sort="url">Url</sort></th>
						<th><sort sort="lastChecks">Last check</sort></th>
						<th><sort sort="renewal">Renewal</sort></th>
						<th v-for="c in urlsChecklist">{{ c.title }}</th>
						<th>Action</th>
						</thead>
						<tbody>
						<template v-for="(row, i) in urlsRows">
							<tr>
								<td>{{ i + 1 }}</td>
								<td>
									<a :href="row.url" target="_blank" rel="noreferrer noopener">
										{{ cutUrl(row.url) }}
									</a>
								</td>
								<td>
									<span v-if="row.lastCheck && row.lastCheck !== '0000-00-00 00:00:00'">
										{{ row.lastCheck }}
									</span>
									<span v-else>-</span>
								</td>
								<td>
									{{ getRenewal(row.renewal) }}
								</td>
								<td v-for="c in urlsChecklist">
									<a
										v-if="row.statuses[c.key]"
										:title="getTitle(row, row.statuses[c.key])"
										class="help"
									>
										<i :class="'fa fas fa-' + checkStatusMap[row.statuses[c.key].status].icon"></i>
									</a>
									<span v-else>-</span>
								</td>
								<td nowrap>
									<span
										class="margin-right-10 link"
										@click="updateCheck(row, row.id, 'urls')"
									>
										<i
											class="fa-fw fas fa-sync"
											:class="[{'fast-spin': row.isUpdating}]"
										></i>
									</span>
									<span
										class="margin-right-10 link"
										@click="showUrlCheckPopup(row)"
									>
										<i class="fa-fw fas fa-edit"></i>
									</span>
									<span
										class="link"
										@click="deleteCheck(row.id, i, 'urls')"
									>
										<i class="fa-fw fas fa-times"></i>
									</span>
								</td>
							</tr>
						</template>
						</tbody>
						<tfoot>
						<tr v-if="!urlsRows.length">
							<td colspan="100" class="text-center">
								No data
							</td>
						</tr>
						</tfoot>
					</table>
				</div>
			</template>
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
					<multi-sel :items="domainSuggestion" v-model="domainPopup.types" class="width100"></multi-sel>
				</div>
				<template v-if="domainPopup.types">
					<div>
						<div class="general">Url of the main mirror:</div>
						<input v-model.lazy.trim="domainPopup.url" class="width100">
					</div>
					<div v-if="domainPopup.types.includes('httpStatus')">
						<div class="general">HTTP st:</div>
						<rich-sel
							:items="httpSuggestion"
							v-model="domainPopup.httpStatus"
							class="width100"
							search-hidden="1"
							disabled="1"
						></rich-sel>
					</div>
					<div v-if="domainPopup.types.includes('ga')">
						<div class="general">GA:</div>
						<input v-model.lazy.trim="domainPopup.ga" class="width100" placeholder="UA-XXXXXXXX-X">
					</div>
					<div v-if="domainPopup.types.includes('robots') || domainPopup.types.includes('disallow')">
						<div class="general">Robots:</div>
						<input v-model.lazy.trim="domainPopup.robots" class="width100">
					</div>
					<div v-if="domainPopup.types.includes('sitemap')">
						<div class="general">Sitemap:</div>
						<textarea v-model.lazy.trim="domainPopup.sitemap" class="width100"></textarea>
					</div>
				</template>
			</div>
			<div slot="footer">
				<div class="clearfix">
					<btn
						@click.native="saveDomainCheck"
						:loading="domainPopup.isSaving"
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
			width="500"
			@close="urlPopup = false"
			no-scroll="1"
		>
			<div slot="header">Url check</div>
			<div>
				<div>
					<div class="general">Types:</div>
					<multi-sel :items="urlSuggestion" v-model="urlPopup.types" class="width100"></multi-sel>
				</div>
				<template v-if="urlPopup.types">
					<div class="clearfix">
						<div class="left" style="width: 59%">
							<div class="general">Url:</div>
							<input v-model.lazy.trim="urlPopup.url" class="width100">
						</div>
						<div class="right" style="width: 39%">
							<div class="general">Renewal:</div>
							<rich-sel :items="renewalsSuggestion" v-model="urlPopup.renewal" class="width100" search-hidden="1"></rich-sel>
						</div>
					</div>
					<div v-if="urlPopup.types.includes('httpStatus')">
						<div class="general">HTTP st:</div>
						<rich-sel :items="httpSuggestion" v-model="urlPopup.httpStatus" class="width100" search-hidden="1"></rich-sel>
					</div>
					<div v-if="urlPopup.types.includes('noindex')">
						<div class="general">Page with noindex:</div>
						<rich-sel :items="noindexSuggestion" v-model="urlPopup.noindex" class="width100" search-hidden="1" no-empty="1"></rich-sel>
					</div>
					<div v-if="urlPopup.types.includes('title')">
						<div class="general">Title:</div>
						<textarea v-model.lazy.trim="urlPopup.title" class="width100"></textarea>
					</div>
					<div v-if="urlPopup.types.includes('description')">
						<div class="general">Description:</div>
						<textarea v-model.lazy.trim="urlPopup.description" class="width100"></textarea>
					</div>
					<div v-if="urlPopup.types.includes('tags')">
						<div class="general">Tags:</div>
						<textarea
							v-model.lazy.trim="urlPopup.tag1"
							class="width100"
						></textarea>
						<textarea
							v-if="urlPopup.tag1 || urlPopup.tag2"
							v-model.lazy.trim="urlPopup.tag2"
							class="width100"
						></textarea>
						<textarea
							v-if="urlPopup.tag2 || urlPopup.tag3"
							v-model.lazy.trim="urlPopup.tag3"
							class="width100"
						></textarea>
					</div>
				</template>
			</div>
			<div slot="footer">
				<div class="clearfix">
					<btn
						@click.native="saveUrlCheck"
						:loading="urlPopup.isSaving"
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

<script>
	import Popup from "../components/Popup";
	import RichSel from "../components/RichSel";
	import MultiSel from "../components/MultiSel";
	import Btn from "../components/Btn";
	import Sort from "../components/Sort";

	export default {
		name: 'autoChecker',
		components: {Sort, Popup, RichSel, MultiSel, Btn},
		data() {
			return {
				init: false,
				portion: 0,
				isNoMore: false,
				isLoadingMore: false,
				domainsIsLoading: false,
				domainsRows: null,
				domainsTotalCnt: null,
				urlsIsLoading: false,
				urlsRows: null,
				urlsTotalCnt: null,
				domainPopup: false,
				urlPopup: false,
				domainChecklist: [
					{
						key: 'httpStatus',
						title: 'Http st.',
						description: 'Http st.'
					},
					{
						key: 'noindex',
						title: 'Noindex',
						description: 'Noindex'
					},
					{
						key: 'robots',
						title: 'Robots',
						description: 'Robots.txt'
					},
					{
						key: 'disallow',
						title: 'Disallow',
						description: 'Disallow: /'
					},
					{
						key: 'sitemap',
						title: 'Sitemap',
						description: 'Sitemap'
					},
					{
						key: 'ga',
						title: 'GA',
						description: 'Google Analytics'
					}
				],
				urlsChecklist: [
					{
						key: 'httpStatus',
						title: 'Http st.',
						description: 'Http st.'
					},
					{
						key: 'noindex',
						title: 'Noindex',
						description: 'Noindex'
					},
					{
						key: 'title',
						title: 'Title',
						description: 'Title'
					},
					{
						key: 'description',
						title: 'Description',
						description: 'Description'
					},
					{
						key: 'tags',
						title: 'Tags',
						description: 'Tags'
					}
				],
				checkStatusMap: [
					{
						icon: 'clock grey',
						title: 'Waiting for check'
					},
					{
						icon: 'check green',
						title: 'Good'
					},
					{
						icon: 'ban red',
						title: 'Bad'
					},
				],
				httpStatuses: [200, 301, 404, 410]
			}
		},
		computed: {
			domainSuggestion() {
				return this.domainChecklist.map(c => {
					return {
						key: c.key,
						val: c.description
					}
				});
			},
			urlSuggestion() {
				return this.urlsChecklist.map(c => {
					return {
						key: c.key,
						val: c.description
					}
				});
			},
			noindexSuggestion() {
				return [
					{key: '0', val: 'No'},
					{key: '1', val: 'Yes'},
				]
			},
			httpSuggestion() {
				return this.httpStatuses.map(st => {
					return {
						key: st,
						val: st
					}
				});
			},
			renewalsSuggestion() {
				return [
					{key: 1, val: 'Everyday'},
					{key: 7, val: 'Once a week'},
					{key: 30, val: 'Once a month'},
				]
			},
		},
		methods: {
			getTitle(row, cell) {
				let title = '';

				if (parseInt(cell.status) === 1 && cell.checkName === 'httpStatus') {
					title = 'status: ' + row.httpStatus;
				}
				if (parseInt(cell.status) === 0) {
					title = this.checkStatusMap[cell.status].title;
				}
				if (parseInt(cell.status) === 2) {
					title = 'response: ' + cell.response;
				}

				return title;
			},
			getRenewal(renewal) {
				let renewals = this.renewalsSuggestion.filter(i => i.key === parseInt(renewal));
				if (renewals.length) return renewals[0].val;

				return '';
			},
			getStatuses() {
				return [
					{key: 1, val: 'Good'},
					{key: 2, val: 'Bad'},
				]
			},
			showDomainCheckPopup(row = false) {
				let params = {};

				if (row) {
					params = {
						...row,
						types: Object.keys(row.statuses).join(',')
					};
				} else {
					params = {
						types: this.domainChecklist.map(c => c.key).join(','),
						httpStatus: 200
					};
				}

				this.domainPopup = params;
			},
			showUrlCheckPopup(row = null) {
				let params = {};

				if (row) {
					params = {
						...row,
						types: Object.keys(row.statuses).join(',')
					};
				} else {
					params = {url: this.domainsRows[0].url || ''};
				}

				this.urlPopup = params;
			},
			async saveDomainCheck() {
				let p = this.domainPopup;
				if (!p.types) return this.domainPopup = false;
				if (!p.url) return alert('Please, enter url');
				if (p.types.includes('httpStatus') && !p.httpStatus) return alert('Please, select http status code');
				if (p.types.includes('ga') && !p.ga) return alert('Please, enter google analytics id');
				if (p.types.includes('robots') && !p.robots) return alert('Please, enter robots url');
				if (p.types.includes('sitemap') && !p.sitemap) return alert('Please, enter sitemap url');

				this.$set(this.domainPopup, 'isSaving', true);
				await this.post('/autochecks/save-domain-check', p);
				this.$delete(this.domainPopup, 'isSaving');
				this.updateRows();
				this.domainPopup = false;
			},
			async saveUrlCheck() {
				let p = this.urlPopup;
				if (!p.types) return this.urlPopup = false;
				if (!p.url) return alert('Please, enter url');
				if (!p.renewal) return alert('Please, select renewal');
				if (p.types.includes('httpStatus') && !p.httpStatus) return alert('Please, select http status code');
				if (p.types.includes('noindex') && !p.noindex) return alert('Please, select noindex availability');
				if (p.types.includes('title') && !p.title) return alert('Please, enter title');
				if (p.types.includes('description') && !p.description) return alert('Please, enter description');
				if (p.types.includes('tags') && !p.tag1 && !p.tag2 && !p.tag3) return alert('Please, enter tags');

				this.$set(this.urlPopup, 'isSaving', true);
				await this.post('/autochecks/save-url-check', p);
				this.$delete(this.urlPopup, 'isSaving');
				this.updateRows();
				this.urlPopup = false;
			},
			async getDomainsRows(opts = {}) {
				if (this.domainsIsLoading) return;

				if (opts.isAppend) {
					if (this.isNoMore) return;

					this.portion++;
					this.isLoadingMore = true;
				} else {
					this.portion = 1;
					this.isNoMore = false;
					this.setLoading(true);

					this.loadDomainsStat();
				}

				this.domainsIsLoading = true;
				let result = await this.get('/autochecks/get-domains-rows', {
					...this.app.query,
					portion: this.portion
				});

				let rows = result.res.rows || [];
				this.domainsIsLoading = false;
				this.isLoadingMore = false;
				this.setLoading(false);

				if (!opts.isAppend) this.domainsRows = [];
				if (!rows.length) return this.isNoMore = true;

				this.domainsRows.push(...rows);
			},
			async loadDomainsStat() {
				this.domainsTotalCnt = -1;
				let result = await this.get('/autochecks/get-domains-rows/stat', this.app.query);
				if (!result || !result.res) return;

				this.domainsTotalCnt = result.res.totalCnt || 0;
			},
			async getUrlsRows() {
				if (this.urlsIsLoading) return;
				if (!this.app.query.domain) {
					this.urlsRows = null;
					return;
				}

				this.loadUrlsStat();
				this.urlsIsLoading = true;
				let result = await this.get('/autochecks/get-urls-rows', this.app.query);

				this.urlsRows = result.res.rows || [];
				this.urlsIsLoading = false;
			},
			async loadUrlsStat() {
				this.urlsTotalCnt = -1;
				let result = await this.get('/autochecks/get-urls-rows/stat', this.app.query);
				if (!result || !result.res) return;

				this.urlsTotalCnt = result.res.totalCnt || 0;
			},
			deleteCheck(id, i, table) {
				this.post('/autochecks/delete-check', {id, table});
				this.$delete(this[table + 'Rows'], i);
			},
			cutUrl(url) {
				let newUrl = url.replace(/https?:\/\//gi, '');
				return '/' + newUrl.split('/').splice(1).join('/');
			},
			async updateCheck(row, id, table) {
				if (row.isUpdating) return;

				this.$set(row, 'isUpdating', true);
				await this.post('/autochecks/robot/check', {id, table});
				this.updateRows();
				this.$set(row, 'isUpdating', false);
			},
			updateRows() {
				this.getDomainsRows();
				this.getUrlsRows();
			},
			onScrollToBottom() {
				if (this.app.query.domain) return;
				this.getDomainsRows({isAppend: true});
			}
		},
		watch: {
			'app.query'() {
				this.updateRows();
			},
			'domainPopup.url'() {
				if (!this.domainPopup) return;
				if (!this.domainPopup.url) return this.domainPopup.robots = '';

				let domain = this.domainPopup.url;
				this.domainPopup.robots = domain + (domain.slice(-1) !== '/' ? '/' : '') + 'robots.txt';
			}
		},
		async mounted() {
			this.setLoading(true);
			let ok = await this.checkAuth();
			if (!ok) return;

			await this.getDomainsRows();
			await this.getUrlsRows();
			this.setLoading(false);
			this.init = true;
		}
	}
</script>

<style scoped>
	.tbl-outer {
		margin: 0 0 30px 0;
	}
	.table-name {
		margin: 30px 0 2px 0;
	}

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

	.margin-right-10 {
		margin-right: 10px;
	}
</style>