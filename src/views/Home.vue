<template>
	<div class="home">
		<div class="general-banner" id="home">
			<img src="../assets/min/IMG_6995.jpg" alt="Свадьба">
		</div>
		<div class="days-to-wedding">
			<div>
				<template v-if="afterWedding">
					<i class="fas fa-heart"></i>
					<br>
					<span>Вместе</span>
				</template>
				<template v-else>
					Осталось до
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
				<div class="img">
					<img src="../assets/min/IMG_20180427_155820.jpg" alt="Максим Дворовой">
				</div>
				<div class="text">
					<h3 class="display-3">Максим Дворовой</h3>
					ЖЕНИХ<br>
					<i class="fas fa-chess-king"></i>
					<br>
					<p>
						С первой нашей встречи я понял, какая она необыкновенная. Прошло уже столько времени, а это чувство не покидает меня и по сей день. А ведь для счастья многого не надо. Всего лишь любимый человек рядом.
					</p>
				</div>
			</div>
			<div>
				<div class="img">
					<img src="../assets/min/photo_2020-05-27_23-31-54_1.jpg" alt="Анастасия Снида">
				</div>
				<div class="text">
					<h3 class="display-3">Анастасия Снида</h3>
					НЕВЕСТА<br>
					<i class="fas fa-chess-queen"></i>
					<br>
					<p>
						С первой нашей встречи каждый мой день наполнен счастьем. Мое внутренее ощущение говорит мне что Максим точно мой человек. С ним я счастлива и чувствую себя любимой. Он изменил мою жизнь.
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
			<div>
				<img src="../assets/min/IMG_0025.jpg">
			</div>
			<div>
				<img src="../assets/min/IMG_20180812_192719.jpg">
			</div>
			<div>
				<img src="../assets/min/IMG_20191214_163604.jpg">
			</div>
			<div>
				<img src="../assets/min/IMG_20180909_185356_1.jpg">
			</div>
			<div>
				<img src="../assets/min/IMG_20190427_124938.jpg">
			</div>
			<div>
				<img src="../assets/min/IMG_20190409_214547.jpg">
			</div>
			<div>
				<img src="../assets/min/IMG_20180618_143818_2.jpg">
			</div>
		</div>
		<div class="program" id="program">
			<h1 class="text-center">ПРОГРАММА ДНЯ</h1>
			<h3 class="text-center">20 ИЮНЯ 2020</h3>
			<div class="timeline">
				<div class="event">
					<div class="time">12:00</div>
					<h2>Свадебная церемония</h2>
					<p>
						Это один из прекраснейших свадебных обрядов, которые несут в себе всю неотъемлемую ценность всего свадебного действия. Церемония будет происходить в ЗАГС г. Вишновое.
					</p>
				</div>
				<div class="event">
					<div class="time">13:00</div>
					<h2>Фотосессия</h2>
					<p>
						Все желающие могут присоединится к сьемке и запечатлить эти эмоции на всю жизнь. Фотосессия будет длится около 2 часов.
					</p>
				</div>
				<div class="event">
					<div class="time">15:30</div>
					<h2>Сбор в ресторане</h2>
					<p>
						Собираем всех гостей на наш праздник, знакомимся с ведущим. Вас ждёт шампанское и приятная музыка.
					</p>
				</div>
				<div class="event">
					<div class="time">16:00</div>
					<h2>Время праздновать</h2>
					<p>
						Начало банкета и веселья. Отрываемся по полной)
					</p>
				</div>
			</div>
		</div>
		<div class="place" id="place">
			<div>
				<img src="../assets/min/josh-felise-jjj1rHyYyG0-unsplash.jpg" alt="ЗАГС Вишневое">
			</div>
			<div class="bg-red">
				<h2>ЗАГС Вишневое</h2>
				<h4>12:00 ул. Европейская, 27А г. Вишневое</h4>
				<div class="line-small"></div>
				<h5>
					ЗАГС города Вишневое расположен в центре города. Он очень аккуратный, просторный и красивый. В торжественном зале проведения свадебной церемонии присутствует большое количество цветов и зеркал.
				</h5>
			</div>
			<div class="bg-blue">
				<h2>Ресторан "Верховина"</h2>
				<h4>15:30 ул. Петропавловская, 24 Софиевская Борщаговка</h4>
				<div class="line-small"></div>
				<h5>
					Загородный ресторан «Верховина» - идеальное место для проведения торжественных мероприятий на высшем уровне. Торжественные банкетные залы на любой вкус, изысканная кухня, приветливый персонал. Меню состоит из украинско-европейской кухни. Ресторан располагается на Окружной, на самой окраине столицы.
				</h5>
			</div>
			<div>
				<img src="../assets/min/w752_h506_crop.jpg" alt="Ресторан 'Верховина'">
			</div>
		</div>
		<div id="mapid"></div>
		<div class="quote text-center">
			<h1>Лучший подарок - ваше участие, спасибо вам</h1>
		</div>
	</div>
</template>

<style scoped>
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
		animation: 2s ease-in-out 0s alternate show_banner;
	}

	@keyframes show_banner {
		0% {
			opacity: .5;
			transform: scale(0.8);
		}

		70% {
			opacity: 1;
			transform: scale(1.02);
		}

		90% {
			transform: scale(0.99);
		}

		100% {
			transform: scale(1);
		}
	}

	.days-to-wedding {
		display: flex;
		justify-content: space-between;
		font-size: 35px;
		background-color: #94b1d2;
		color: white;
		text-align: center;
		overflow: hidden;
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

	.parallax-banner::after {
		content: ' ';
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		background-image: url('../assets/min/drew-coffman-llWjwo200fo-unsplash.jpg');
		background-attachment: fixed;
		background-repeat: no-repeat;
		background-size: cover;
		z-index: -1;
		filter: blur(5px);
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
		position: relative;
		overflow: hidden;
		width: 100%;
		height: 450px;
		margin: 0 auto;
	}

	.parallax-banner > div:first-child .img img {
		position: absolute;
		width: 100%;
		left: 0;
		top: -90px;
	}

	.parallax-banner > div:last-child .img img {
		position: absolute;
		width: 100%;
		left: 0;
		top: -10px;
	}

	.parallax-banner > div .text h3 {
		margin-bottom: 10px;
	}

	.parallax-banner > div .text i {
		color: #ffd390;
		margin: 15px 0;
	}

	.photos {
		display: flex;
		justify-content: space-between;
		background: #2b2b2b;
		flex-wrap: wrap;
	}

	.photos > div {
		width: 25%;
		height: calc(100vw / 4);
		position: relative;
		overflow: hidden;
	}

	.photos > div > div {
		display: table-cell;
		vertical-align: middle;
	}

	.photos > div > div h2, .photos > div > div h4 {
		margin: 7px;
		color: white;
	}

	.photos > div > div a {
		color: white;
		transition: .5s;
	}

	.photos > div > div a:hover {
		opacity: .7;
	}

	.photos > div > img {
		position: absolute;
	}

	.photos > div:nth-child(1) {
		display: table;
	}

	.photos > div:nth-child(2) img {
		width: 100%;
		top: -35px;
		left: 0;
	}

	.photos > div:nth-child(3) img {
		height: 100%;
		left: -30%;
	}

	.photos > div:nth-child(4) img {
		width: 100%;
		top: -18%;
	}

	.photos > div:nth-child(5) img {
		height: 100%;
		left: -20%;
	}

	.photos > div:nth-child(6) img {
		height: 100%;
		left: -20%;
	}

	.photos > div:nth-child(7) img {
		height: 100%;
		left: -10%;
	}

	.photos > div:nth-child(8) img {
		height: 100%;
		left: -20%;
	}

	.program {
		padding: 60px 20px;
		background: white;
	}

	.timeline {
		position: relative;
		max-width: 600px;
		margin: 0 auto;
		padding-left: 30px;
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
		margin: 30px 0 30px 70px;
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

	.timeline .event p {
		font-size: 20px;
	}

	.place {
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
		background: white;
	}

	.place > div:nth-child(1), .place > div:nth-child(4) {
		width: 50%;
		min-height: 300px;
		overflow: hidden;
	}

	.place > div:nth-child(2), .place > div:nth-child(3) {
		width: calc(50% - 80px);
		min-height: 300px;
		padding: 40px;
	}

	.place > div:nth-child(4) {
		filter: opacity(.8);
	}

	.place > div:first-child img, .place > div:last-child img {
		object-fit: cover;
		width: 100%;
		height: 100%;
	}

	.place > div .line-small {
		background-color: white;
		height: 2px;
		width: 40px;
		margin-bottom: 20px;
	}

	.place > div > h2 {
		margin: 0;
		color: white;
	}

	.place > div > h4 {
		margin: 15px 0 20px 0;
		color: white;
	}

	.place > div > h5 {
		margin: 15px 0 20px 0;
		color: white;
	}

	.place .bg-red {
		background: #bd5e62;
		color: white;
	}

	.place .bg-blue {
		background: #4d6989;
		color: white;
	}

	.quote {
		padding: 60px 20px;
		background: #516e90;
		color: white;
	}

	.quote h1 {
		color: white;
	}

	#mapid {
		height: 400px;
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

		.days-to-wedding > div:first-child {
			display: none;
		}
	}

	@media (max-width: 700px) {
		.parallax-banner::after {
			background-attachment: unset;
			filter: blur(15px);
		}

		.photos > div {
			width: 50%;
			height: calc(100vw / 2);
		}

		.place > div {
			width: 100% !important;
		}

		.place > div:nth-child(1) {
			order:1;
		}

		.place > div:nth-child(2) {
			order:2;
		}

		.place > div:nth-child(3) {
			order:4;
		}

		.place > div:nth-child(4) {
			order:3;
		}
	}

	@media (max-width: 600px) {
		.days-to-wedding {
			flex-direction: column;
		}

		.days-to-wedding > div:first-child {
			border-right: unset;
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
			map: null,
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
			let wedding = new Date(2020, 5, 20, 12);
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
		},
		initMap() {
			this.mymap = L.map('mapid').setView([50.4255677, 30.3695225], 12);
			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
				maxZoom: 18,
				minZoom: 4,
				id: 'mapbox/streets-v11',
				tileSize: 512,
				zoomOffset: -1,
				accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
			}).addTo(this.mymap);

			let restaurant = L.marker([50.4439034,30.3564596]).addTo(this.mymap);
			restaurant.bindPopup("<div style='text-align: center; font-size: 16px'>Ресторан</div>");

			let weeding = L.marker([50.3920556, 30.3731424]).addTo(this.mymap);
			weeding.bindPopup("<div style='text-align: center; font-size: 16px'>ЗАГС</div>");
		}
	},
	async mounted() {
		setInterval(this.calcWeddingDate, 1000);
		this.initMap();
	}
}
</script>
