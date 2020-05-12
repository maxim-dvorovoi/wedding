<template>
	<div class="login" v-if="init">
		<div class="content-center">
			<div class="login-form">
				<div>
					<div class="general">Email:</div>
					<input class="width100" v-model="email" @keyup.enter="login()" id="username">
				</div>
				<div>
					<div class="general">Password:</div>
					<input type="password" class="width100" v-model="password" @keyup.enter="login()" id="password">
				</div>
				<div class="text-center">
					<btn @click.native="login()">Login</btn>
				</div>
			</div>
			<transition name="fade-down">
				<h5 v-show="error" class="red text-center">Error: Invalid email or password</h5>
			</transition>
		</div>
	</div>
</template>

<script>
	import Btn from "../components/Btn";
	export default {
		name: 'login',
		components: {Btn},
		data() {
			return {
				init: false,
				error: false,
				email: null,
				password: null
			};
		},
		methods: {
			async login() {
				if (!this.email || !this.password) return this.error = true;

				let data = await this.post('/auth/login', {email: this.email, password: this.password});
				this.error = !data || data.err || !data.res;
				if (this.error) return;

				this.setCookie('token', data.res, {path: '', expires: 365 * 3});
				this.$router.push({name: "home"});
			}
		},
		mounted() {
			this.removeCookie('token', {path: ''});
			this.setLoading(false);
			this.init = true;
		}
	}
</script>

<style scoped>
	.login-form {
		max-width: 400px;
		margin: 50px auto;
		padding: 20px 10px;
		border: 1px solid #dcdcdc;
		border-radius: 10px;
		box-shadow: 0 15px 35px rgba(0,0,0,.1), 0 5px 15px rgba(0,0,0,.3);
	}

	.login-form > div {
		margin: 30px;
	}
</style>