<template>
	<div class="not-found" v-if="init">
		<div class="content-center text-center">
			<br>
			<h2>Error: 404 Page not found</h2>
			<h3>
				Please go to the
				<router-link class="green" to="/">home page</router-link>
			</h3>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'notFound',
		data() {
			return {
				init: false
			}
		},
		methods: {

		},
		async mounted() {
			this.setLoading(true)
			let ok = await this.checkAuth();
			if (!ok) return;

			this.setLoading(false);
			this.init = true;
		}
	}
</script>
