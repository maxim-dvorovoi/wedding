<template>
	<div class="home">
		<div class="general-banner" id="home">
			<img src="/IMG_6995.jpeg">
		</div>
		<div class="days-to-wedding">
			<div>
				<template v-if="afterWedding">
					<i class="fas fa-heart"></i>
					<br>
					<span>Вместе</span>
				</template>
				<template v-else>
					Осталось дней до
					<br>
					<span>Нашей свадьбы</span>
				</template>
			</div>
			<div>
				{{ daysLeft }}
				<br>
				Дней
			</div>
			<div>
				{{ hoursLeft }}
				<br>
				Часов
			</div>
			<div>
				{{ minuteLeft }}
				<br>
				Минут
			</div>
			<div>
				{{ secondsLeft }}
				<br>
				Секунд
			</div>
		</div>
		<div class="parallax-banner" id="aboutUs">
			<div>
				<div class="img"></div>
				<div class="text">
					<h3 class="display-3">Максим Дворовой</h3>
					ЖЕНИХ<br>
					<i class="fas fa-chess-king"></i>
					<br>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat purus sed tempus ornare. Sed convallis eu orci ut sodales. Nam rhoncus laoreet elit, a conimentum augue tempor itae in faucibus cura
					</p>
				</div>
			</div>
			<div>
				<div class="img"></div>
				<div class="text">
					<h3 class="display-3">Анастасия Снида</h3>
					НЕВЕСТА<br>
					<i class="fas fa-chess-queen"></i>
					<br>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat purus sed tempus ornare. Sed convallis eu orci ut sodales. Nam rhoncus laoreet elit, a conimentum augue tempor itae in faucibus cura
					</p>
				</div>
			</div>
		</div>
		<div class="grid-2x2" id="place">
			<div></div>
			<div class="bg-red">
				<h2>ЗАГС Вишневое</h2>
				<h4>12:00 ул. Европейская, 27А г. Вишневое</h4>
				<div class="line-small"></div>
				<h5>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					Vivamus feugiat purus sed tempus ornare.
					Sed convallis eu orci ut sodales.
					Nam rhoncus laoreet elit, a condimentum augue tempor vitae in faucibus cura.
					Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					Vivamus feugiat purus sed tempus ornare.
					Sed convallis eu orci ut sodales.
					Nam rhoncus laoreet elit, a condimentum augue tempor.
				</h5>
			</div>
			<div class="bg-blue">
				<h2>Ресторан "Верховина"</h2>
				<h4>15:30 ул. Петропавловская, 24 Киевская обл.</h4>
				<div class="line-small"></div>
				<h5>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					Vivamus feugiat purus sed tempus ornare.
					Sed convallis eu orci ut sodales.
					Nam rhoncus laoreet elit, a condimentum augue tempor vitae in faucibus cura.
					Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					Vivamus feugiat purus sed tempus ornare.
					Sed convallis eu orci ut sodales.
					Nam rhoncus laoreet elit, a condimentum augue tempor.
				</h5>
			</div>
			<div></div>
		</div>
		<div class="program" id="program">
			<h1 class="text-center">ПРОГРАММА ДНЯ</h1>
			<h3 class="text-center">20 ИЮНЯ 2020</h3>
			<div class="timeline">
				<div class="event">
					<div class="time">11:30</div>
					<h2>Свадебная церемония</h2>
					<p>
						Nam rhoncus laoreet elit, a condimentum augue tempor vitae in faucibus cura.
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Vivamus feugiat purus sed tempus ornare.
						Sed convallis eu orci ut sodales.
						Nam rhoncus laoreet elit, a condimentum augue tempor.
					</p>
				</div>
				<div class="event">
					<div class="time">11:30</div>
					<h2>Фотосессия</h2>
					<p>
						Nam rhoncus laoreet elit, a condimentum augue tempor vitae in faucibus cura.
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Vivamus feugiat purus sed tempus ornare.
						Sed convallis eu orci ut sodales.
						Nam rhoncus laoreet elit, a condimentum augue tempor.
					</p>
				</div>
				<div class="event">
					<div class="time">11:30</div>
					<h2>Ресторан</h2>
					<p>
						Nam rhoncus laoreet elit, a condimentum augue tempor vitae in faucibus cura.
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Vivamus feugiat purus sed tempus ornare.
						Sed convallis eu orci ut sodales.
						Nam rhoncus laoreet elit, a condimentum augue tempor.
					</p>
				</div>
				<div class="event">
					<div class="time">11:30</div>
					<h2>Время праздновать</h2>
					<p>
						Nam rhoncus laoreet elit, a condimentum augue tempor vitae in faucibus cura.
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Vivamus feugiat purus sed tempus ornare.
						Sed convallis eu orci ut sodales.
						Nam rhoncus laoreet elit, a condimentum augue tempor.
					</p>
				</div>
			</div>
		</div>
		<div class="photos" id="photo">
			<div>
				<div>
					<h2 class="text-center">Наши фото</h2>
					<h4 class="text-center">
						<a href="https://www.instagram.com/d_mas9/" target="_blank">
							d_mas9
							<i class="fab fa-instagram"></i>
						</a>
					</h4>
					<h4 class="text-center">
						<a href="https://www.instagram.com/nusia_dv/" target="_blank">
							nusia_dv
							<i class="fab fa-instagram"></i>
						</a>
					</h4>
				</div>
			</div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
	</div>
</template>

<style scoped>
	.timeline {
		position: relative;
		max-width: 600px;
		margin: 0 auto;
		padding: 0 30px;
	}

	.timeline:before {
		content: "";
		position: absolute;
		width: 1px;
		background-color: #ddd;
		height: 100%;
	}

	.timeline .event {
		position: relative;
		margin: 30px 30px 30px 70px;
	}

	.timeline .event .time {
		background-color: #bd5e62;
		color: white;
		font-size: 20px;
		position: absolute;
		width: 60px;
		text-align: center;
		padding: 7px;
		border-radius: 10px;
		top: 5px;
		left: -105px;
	}

	.general-banner {
		display: flex;
		align-items: center;
		justify-content: center;
		margin-top: -80px;
		background-color: #fdf3f1;
		overflow: hidden;
		height: 100vh;
	}

	.general-banner img {
		display: block;
		margin: 0 auto;
		height: 95vh;
		object-fit: cover;
	}

	.days-to-wedding {
		display: flex;
		justify-content: space-between;
		font-size: 35px;
		background-color: #94b1d2;
		color: white;
		text-align: center;
	}

	.days-to-wedding > div {
		padding: 25px;
	}

	.days-to-wedding > div > i {
		font-size: 25px;
	}

	.days-to-wedding > div:first-child {
		border-right: 1px solid white;
	}

	.days-to-wedding > div:first-child > span {
		font-weight: 700;
	}

	.parallax-banner {
		display: flex;
		justify-content: space-around;
		flex-wrap: wrap;
		min-height: 100%;
		position: relative;
		overflow: hidden;
		padding: 100px 10px;
		color: black;
	}

	.parallax-banner:before {
		content: ' ';
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		background: url('/drew-coffman-llWjwo200fo-unsplash.jpg') no-repeat fixed;
		background-size: cover;
		z-index: -1;
		filter: blur(4px) opacity(0.9);
	}

	.parallax-banner > div {
		margin: 10px;
		text-align: center;
		background: white;
		font-size: 25px;
		max-width: 450px;
	}

	.parallax-banner > div .text {
		padding: 0 30px 30px;
	}

	.parallax-banner > div .img {
		width: 100%;
		height: 450px;
		margin: 0 auto;
		filter: opacity(0.95);
	}

	.parallax-banner > div:first-child .img {
		background: url('/IMG_20180427_155820.jpg') no-repeat center;
		background-size: cover;
	}

	.parallax-banner > div:last-child .img {
		background: url('/00100sPORTRAIT_00100_BURST20190427124526871_COVER.jpg') no-repeat center;
		background-size: cover;
	}

	.parallax-banner > div .text h3 {
		margin-bottom: 10px;
	}

	.parallax-banner > div .text i {
		color: #ffd390;
		margin: 15px 0;
	}

	.grid-2x2 {
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
		background: white;
	}

	.grid-2x2 > div {
		width: calc(50% - 80px);
		min-height: 300px;
		padding: 40px;
	}

	.grid-2x2 > div:first-child {
		background: url('/josh-felise-jjj1rHyYyG0-unsplash.jpg') no-repeat center;
		background-size: cover;
	}

	.grid-2x2 > div:last-child {
		background: url('/w752_h506_crop.jpg') no-repeat center;
		background-size: cover;
		filter: opacity(.8);
	}

	.grid-2x2 > div .line-small {
		background-color: white;
		height: 2px;
		width: 40px;
		margin-bottom: 20px;
	}

	.grid-2x2 > div > h2 {
		margin: 0;
		color: white;
	}

	.grid-2x2 > div > h4 {
		margin: 15px 0 20px 0;
		color: white;
	}

	.grid-2x2 > div > h5 {
		margin: 15px 0 20px 0;
		color: white;
	}

	.grid-2x2 .bg-red {
		background: #bd5e62;
		color: white;
	}

	.grid-2x2 .bg-blue {
		background: #4d6989;
		color: white;
	}

	.program {
		padding: 60px 20px;
		background: white;
	}

	.photos {
		display: flex;
		justify-content: space-between;
		background: dimgrey;
		flex-wrap: wrap;
	}

	.photos > div {
		width: 25%;
		height: calc(100vw / 4);
	}

	.photos > div > div {
		display: table-cell;
		vertical-align: middle;
	}

	.photos > div > div h2, .photos > div > div h4 {
		margin: 10px;
		color: white;
	}

	.photos > div > div a {
		color: white;
		transition: .5s;
	}

	.photos > div > div a:hover {
		opacity: .7;
	}

	.photos > div:nth-child(1) {
		display: table;
		background-color: dimgrey;
	}

	.photos > div:nth-child(2) {
		background: url('/IMG_0025.jpg') no-repeat center;
		background-size: cover;
		background-position-y: 35%;
	}

	.photos > div:nth-child(3) {
		background: url('/IMG_20180812_192719.jpg') no-repeat center;
		background-size: cover;
		background-position-x: 80%;
	}

	.photos > div:nth-child(4) {
		background: url('/IMG_20191214_163604.jpg') no-repeat center;
		background-size: cover;
		background-position-y: 40%;
	}

	.photos > div:nth-child(5) {
		background: url('/IMG_20180909_185356_1.jpg') no-repeat center;
		background-size: cover;
	}

	.photos > div:nth-child(6) {
		background: url('/IMG_20190427_124938.jpg') no-repeat center;
		background-size: cover;
	}

	.photos > div:nth-child(7) {
		background: url('/IMG_20190409_214547.jpg') no-repeat center;
		background-size: cover;
	}

	.photos > div:nth-child(8) {
		background: url('/IMG_20180618_143818_2.jpg') no-repeat center;
		background-size: cover;
		background-position-x: 65%;
	}

	@media (max-width: 1024px) {
		.general-banner {
			margin-top: -60px;
			background-color: #fdf3f1;
		}
	}

	@media (max-width: 900px) {
		.general-banner img {
			width: 70vh;
			height: 70vh;
		}

		.days-to-wedding > div[data-v-fae5bece]:first-child {
			display: none;
		}
	}

	@media (max-width: 700px) {
		.grid-2x2 > div {
			width: 100%;
		}

		.grid-2x2 > div:nth-child(1) {
			order:1;
		}

		.grid-2x2 > div:nth-child(2) {
			order:2;
		}

		.grid-2x2 > div:nth-child(3) {
			order:4;
		}

		.grid-2x2 > div:nth-child(4) {
			order:3;
		}

		.photos > div {
			width: 50%;
			height: calc(100vw / 2);
		}
	}

	@media (max-width: 600px) {
		.days-to-wedding {
			flex-direction: column;
		}

		.days-to-wedding > div[data-v-fae5bece]:first-child {
			display: block;
		}

		.days-to-wedding > div:not(:last-child) {
			border-bottom: 1px solid white;
		}
	}

	@media (max-width: 500px) {
		.parallax-banner > div .img {
			height: 300px;
		}
	}

	@media (max-width: 370px) {
		.photos > div {
			width: 100%;
			height: 100vw;
		}
	}
</style>

<script>
export default {
	name: 'home',
	data() {
		return {
			afterWedding: false,
			daysLeft: '00',
			hoursLeft: '00',
			minuteLeft: '00',
			secondsLeft: '00'
		}
	},
	methods: {
		calcWeddingDate() {
			let today = new Date();
			let wedding = new Date(2020, 5, 20, 11, 30);
			let diff = Math.floor((wedding.getTime() - today.getTime()) / 1000);

			if (diff < 0) {
				diff = Math.abs(diff);
				this.afterWedding = true;
			}

			let oneDay = 60*60*24;
			let oneHour = 60*60;
			let oneMinute = 60;

			this.daysLeft = Math.floor(diff / oneDay);

			diff = diff - this.daysLeft * oneDay;
			this.hoursLeft = Math.floor(diff / oneHour);

			diff = diff - this.hoursLeft * oneHour;
			this.minuteLeft = Math.floor(diff / oneMinute);

			diff = diff - this.minuteLeft * oneMinute;
			this.secondsLeft = Math.floor(diff);
		}
	},
	async mounted() {
		setInterval(this.calcWeddingDate, 1000);
	}
}
</script>
