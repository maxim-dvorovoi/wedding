<template>
	<div class="not-found">
		<div class="content-center text-center">
			<br>
			<h2>Ошибка: 404 Такой страницы не существует</h2>
			<h3>
				Пожалуйста перейдите на
				<router-link to="/">главную</router-link>
			</h3>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'notFound'
	}
</script>
