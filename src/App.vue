<template>
	<div id="app">
		<div v-if="loading" id="loader" class="loader">
			<div class="dot-1"></div>
			<div class="dot-2"></div>
			<div class="dot-3"></div>
		</div>

		<div id="index" :class="loading ? 'main' : 'main-active'">
			<div class="side" :class="[{'active': $store.state.sideBar}]" ref="side">
				<Header/>
				<div class="content" id="content">
					<transition name="fade">
						<div class="content-loader" v-show="contentLoading">
							<i class="fas fa-spinner fast-spin"></i>
							Loading...
						</div>
					</transition>
					<router-view ref="appView"/>
				</div>
				<Footer/>
			</div>
			<SideBar/>
		</div>
	</div>
</template>

<script>
	import Header from './components/Header.vue'
	import SideBar from './components/SideBar.vue'
	import Footer from './components/Footer.vue'

	export default {
		components: {
			Header,
			SideBar,
			Footer
		},
		data() {
			return {
				query: {},
				params: {},
				hash: null,
				routeName: null,
				loading: true,
				contentLoading: false,
				scrollBtnShown: false,
				win: {
					width: null,
					height: null
				},
			};
		},
		methods: {
			setPageLoading() {
				setTimeout(() => this.loading = false, 700);
			},
			updateWinSize() {
				this.win.width = this.getWinWidth();
				this.win.height = this.getWinHeight();
			},
			initWin() {
				this.updateWinSize();
				window.addEventListener('resize', this.updateWinSize);
			},
			updateRouteParams() {
				this.query = { ...this.$route.query };
				this.params = { ...this.$route.params };
				this.hash = this.$route.hash;
				this.routeName = this.$route.name;
			},
		},
		watch: {
			$route() {
				this.updateRouteParams();
			}
		},
		async mounted() {
			this.initWin();
			this.updateRouteParams();

			window.onload = this.setPageLoading;
		}
	}
</script>

<style>
	#app {
		width: 100%;
		height: 100%;
		background-color: white;
	}

	* {
		font-family: 'Cormorant Infant', serif;
	}

	html {
		height: 100%;
		text-rendering: optimizeLegibility;
	}

	body {
		margin: 0;
		width: 100%;
		height: 100%;
		-webkit-tap-highlight-color: transparent;
		overflow-x: hidden;
	}

	h1, h2, h3, h4, h5, h6 {
		color: #3e3e3e;
		line-height: 1.3;
		font-weight: 300;
	}

	hr {
		border: none;
		color: lightgray;
		background-color: lightgray;
		height: 1px;
	}

	a {
		color: rgb(228, 161, 152);
		text-decoration: none;
	}

	.h1, h1 {
		font-size: 2.5rem;
	}

	.h2, h2 {
		font-size: 2rem;
	}

	.h3, h3 {
		font-size: 1.75rem;
	}

	.h4, h4 {
		font-size: 1.5rem;
	}

	.h5, h5 {
		font-size: 1.25rem;
	}

	.h6, h6 {
		font-size: 1rem;
	}

	.display-1, .display-2, .display-3, .display-4 {
		font-weight: 600;
	}

	.display-1 {
		font-size: 3.3rem;
	}

	.display-2 {
		font-size: 2.75rem;
	}

	.display-3 {
		font-size: 2.1875rem;
	}

	.display-4 {
		font-size: 1.6275rem;
	}

	.general {
		font-weight: 500;
		color: #303030;
	}

	.strong {
		font-weight: 500;
	}

	.bold {
		font-weight: 600;
	}

	.upper {
		text-transform: uppercase;
	}

	.heading {
		letter-spacing: .025em;
		font-size: .95rem;
		text-transform: uppercase;
		font-weight: 400;
	}

	.inlines-blocks > * {
		display: inline-block;
	}

	.green {
		color: #366356;
	}

	.blue {
		color: #476fca;
	}

	.yellow {
		color: #ffb822;
	}

	.grey {
		color: dimgrey;
	}

	.red {
		color: #db3f3f;
	}

	.pointer {
		cursor: pointer;
	}

	.help{
		cursor: help;
	}

	.clearfix:after {
		display: block;
		content: "";
		clear: both;
	}

	.width100 {
		width: 100%;
	}

	.main {
		position: absolute;
		overflow: hidden;
		opacity: 0;
		height: 0;
	}

	.main-active {
		height: 100%;
		position: static;
		opacity: 1;
		transition: opacity .5s ease-in-out;
	}

	.main-active > .side {
		display: flex;
		flex-direction: column;
		min-height: 100%;
		background-color: white;
		transition: transform .4s ease-in-out;
		transform: translateX(0px);
		z-index: 1;
		position: relative;
		will-change: transform;
	}

	.content-center {
		max-width: 1024px;
		margin: 0 auto;
		padding: 0 20px;
	}

	.content {
		flex: 1 0 auto;
		box-sizing: border-box;
	}

	.text-center {
		text-align: center;
	}

	.left {
		float: left;
	}

	.right {
		float: right;
	}

	.link {
		cursor: pointer;
		color: rgb(228, 161, 152);
	}

	.fade-enter-active, .fade-leave-active {
		transition: .2s;
	}

	.fade-enter, .fade-leave-to {
		opacity: 0;
	}

	.fade-down-enter-active, .fade-down-leave-active {
		transition: .2s;
	}

	.fade-down-enter, .fade-down-leave-to {
		opacity: 0;
		transform: translateY(-3px);
	}

	label.chb {
		display: inline-block !important;
		font-weight: 500;
	}

	input, textarea {
		border: 1px solid #b8b8b8;
		box-sizing: border-box;
		border-radius: 5px;
		padding: 5px;
		font-size: 14px;
	}

	input:focus, textarea:focus {
		border-color: rgb(228, 161, 152);
		outline: none ;
	}

	label.chb input[type="checkbox"] {
		position: relative;
		cursor: pointer;
	}

	label.chb input[type="checkbox"]:after {
		content: "\002713";
		display: inline-block;
		position: absolute;
		margin: -1px 0 0;
		top: -1px;
		width: 16px;
		height: 16px;
		line-height: 16px;
		text-align: center;
		color: #fff;
		background: #fff;
		border: 1px solid #ccc;
		border-radius: 3px;
		transition: color 0.1s;
	}

	label.chb input[type="checkbox"]:checked:after {
		color: #000;
		transition: color 0.2s;
	}

	label.chb input[type="checkbox"] + span {
		padding: 0 5px;
		cursor: pointer;
	}

	label.chb input[type="checkbox"]:checked + span {
		opacity: 1;
	}

	label.chb input[type="checkbox"]:disabled {
		opacity: 0.5;
		cursor: default;
	}

	label.chb input[type="checkbox"]:disabled + span {
		opacity: 0.5 !important;
		cursor: default;
	}

	label.chb:hover input[type="checkbox"] + span {
		opacity: 1;
	}

	.filters {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(173px, 1fr));
		grid-gap: 2vw;
		margin: 25px auto;
	}

	.filters .filter {
		position: relative;
		font-size: 15px;
		height: 55px;
	}

	.filters .filter > .richsel, .filters .filter > .multisel{
		width: 100%;
	}

	.filters .filter > .btn {
		position: absolute;
		bottom: 0;
	}

	.tbl-outer {
		width: 100%;
		margin: 30px 0;
	}

	table.general-tbl {
		width: 100%;
		font-size: 14px;
		box-shadow: 0 3px 5px -2px rgba(0,0,0,0.5);
		border-collapse: collapse;
		border: 1px solid #dddddd;
		box-sizing: border-box;
	}

	table.general-tbl th {
		position: sticky;
		top: 79px;
		padding: 0;
		color: white;
		font-weight: 500;
		background: rgb(228, 161, 152);
		z-index: 1;
		text-align: start;
	}

	table.general-tbl th, table td {
		padding: 5px 8px;
	}

	table.hover-tbl tr:hover td {
		background: #fafafa;
	}

	.content-loader {
		position: absolute;
		padding: 10px 20px;
		left: 50%;
		margin-left: -65px;
		background: white;
		z-index: 1000;
		top: 120px;
		box-shadow: 0 3px 7px 0 rgba(0, 0, 0, 0.3);
	}

	.fast-spin {
		animation-name: spin;
		animation-duration: 700ms;
		animation-iteration-count: infinite;
		animation-timing-function: linear;
	}

	@keyframes spin {
		from {
			transform: rotate(0deg);
		}

		to {
			transform: rotate(360deg);
		}
	}

	.loader {
		position: absolute;
		top: 50%;
		left: 50%;
		width: 220px;
		height: 220px;
		margin-top: -110px;
		margin-left: -110px;
		border-radius: 2px;
		color: #bd5e62;
	}

	.dot-1 {
		position: absolute;
		z-index: 3;
		width: 30px;
		height: 30px;
		top: 95px;
		left: 95px;
		background-color: #bd5e62;
		border-radius: 50%;
		-webkit-animation-fill-mode: both;
		animation-fill-mode: both;
		-webkit-animation: jump-jump-1 1s cubic-bezier(0.21, 0.98, 0.6, 0.99) infinite alternate;
		animation: jump-jump-1 1s cubic-bezier(0.21, 0.98, 0.6, 0.99) infinite alternate;
	}

	.dot-2 {
		position: absolute;
		z-index: 2;
		width: 60px;
		height: 60px;
		top: 80px;
		left: 80px;
		background-color: #bd5e62;
		border-radius: 50%;
		-webkit-animation-fill-mode: both;
		animation-fill-mode: both;
		-webkit-animation: jump-jump-2 1s cubic-bezier(0.21, 0.98, 0.6, 0.99) infinite alternate;
		animation: jump-jump-2 1s cubic-bezier(0.21, 0.98, 0.6, 0.99) infinite alternate;
	}

	.dot-3 {
		position: absolute;
		z-index: 1;
		width: 90px;
		height: 90px;
		top: 65px;
		left: 65px;
		background-color: #bd5e62;
		border-radius: 50%;
		-webkit-animation-fill-mode: both;
		animation-fill-mode: both;
		-webkit-animation: jump-jump-3 1s cubic-bezier(0.21, 0.98, 0.6, 0.99) infinite alternate;
		animation: jump-jump-3 1s cubic-bezier(0.21, 0.98, 0.6, 0.99) infinite alternate;
	}

	@-webkit-keyframes jump-jump-1 {
		0%, 70% {
			-webkit-box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		100% {
			-webkit-box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			-webkit-transform: scale(1);
			transform: scale(1);
		}
	}

	@keyframes jump-jump-1 {
		0%, 70% {
			-webkit-box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		100% {
			-webkit-box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			-webkit-transform: scale(1);
			transform: scale(1);
		}
	}
	@-webkit-keyframes jump-jump-2 {
		0%, 40% {
			-webkit-box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		100% {
			-webkit-box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			-webkit-transform: scale(1);
			transform: scale(1);
		}
	}
	@keyframes jump-jump-2 {
		0%, 40% {
			-webkit-box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		100% {
			-webkit-box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			-webkit-transform: scale(1);
			transform: scale(1);
		}
	}
	@-webkit-keyframes jump-jump-3 {
		0%, 10% {
			-webkit-box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		100% {
			-webkit-box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			-webkit-transform: scale(1);
			transform: scale(1);
		}
	}
	@keyframes jump-jump-3 {
		0%, 10% {
			-webkit-box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			box-shadow: 2px 2px 3px 2px rgba(0, 0, 0, 0.2);
			-webkit-transform: scale(0);
			transform: scale(0);
		}
		100% {
			-webkit-box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			box-shadow: 10px 10px 15px 0 rgba(0, 0, 0, 0.3);
			-webkit-transform: scale(1);
			transform: scale(1);
		}
	}

	::-webkit-scrollbar {
		width: 4px;
		height: 4px;
	}

	::-webkit-scrollbar-button {
		width: 0;
		height: 0;
	}

	::-webkit-scrollbar-thumb {
		background-color: #555;
		border: 0 none #fff;
		border-radius: 2px;
		transition: all 0.5s;
	}

	:hover::-webkit-scrollbar-thumb {
		background-color: #333;
	}

	::-webkit-scrollbar-thumb:hover {
		background-color: #000;
	}

	::-webkit-scrollbar-thumb:active {
		background-color: #000;
	}

	::-webkit-scrollbar-track {
		background-color: #fff;
		border: 0 none #fff;
		border-radius: 5px;
	}

	::-webkit-scrollbar-track:hover {
		background-color: #fff;
	}

	::-webkit-scrollbar-track:active {
		background-color: #fff;
	}

	::-webkit-scrollbar-corner {
		background-color: #fff;
	}

	@media (max-width: 1024px) {
		.tbl-outer {
			overflow-x: auto;
		}

		table.general-tbl th {
			position: static;
		}

		.main-active > .side.active {
			transform: translateX(-250px);
		}
	}

	@media (max-width: 400px) {
		.content-center {
			padding: 0 10px;
		}
	}
</style>