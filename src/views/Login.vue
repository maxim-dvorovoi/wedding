<template>
	<div class="login">
		<div class="content-center">
			<div class="login-form">
				<div>
					<div class="general">Email:</div>
					<input class="width100">
				</div>
				<div>
					<div class="general">Password:</div>
					<input class="width100">
				</div>
				<div class="text-center">
					<btn>Login</btn>
				</div>
			</div>
			<br>
		</div>
	</div>
</template>

<script>
	import Btn from "../components/Btn";
	export default {
		name: 'login',
		components: {Btn},
		methods: {

		},
	}
</script>

<style scoped>
	.login-form {
		max-width: 400px;
		margin: 50px auto;
		border: 1px solid #bcbcbc;
		border-radius: 10px;
	}

	.login-form > div {
		margin: 30px;
	}
</style>