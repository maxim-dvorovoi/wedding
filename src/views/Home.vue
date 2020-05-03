<template>
	<div class="home" v-if="init">
		<div class="content-center">
			<br>
			<h2 class="display-2 text-center">Welcome to the SEO Admin</h2>
			<h2 class="text-center">
				SEO Utils
				<i class="fas fa-sort-amount-up-alt green font24"></i>
			</h2>
			<div class="tbl-outer">
				<div class="pages text-center">
					<div>
						<router-link to="/auto-checker">
							<i class="fas fa-check"></i>
							Auto checker
						</router-link>
						&nbsp;- domains and urls checks
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
	.pages {
		font-size: 20px;
		font-weight: 300;
		margin: 0 auto;
		max-width: 500px;
	}

	.pages a {
		font-weight: 400;
	}

	.font24 {
		font-size: 24px;
	}
</style>

<script>
export default {
	name: 'home',
	data() {
		return {
			init: false,
		}
	},
	methods: {

	},
	async mounted() {
		this.setLoading(true);
		let ok = await this.checkAuth();
		if (!ok) return;

		this.setLoading(false);
		this.init = true;
	}
}
</script>
