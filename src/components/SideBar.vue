<template>
    <div class="sidebar" v-mousedown-outside="outsideClick" id="sidebar">
        <div class="routes">
            <router-link to="/" @click.native="scrollTo('home')">
                <div>Главная</div>
            </router-link>
            <router-link to="/" @click.native="scrollTo('aboutUs')">
                <div>О нас</div>
            </router-link>
            <router-link to="/" @click.native="scrollTo('program')">
                <div>Программа</div>
            </router-link>
            <router-link to="/" @click.native="scrollTo('photo')">
                <div>Фото</div>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SideBar",
        data() {
            return {
            }
        },
        methods: {
            scrollTo(id) {
                this.showHideSideBar();
                this.scrollToTag(id);
            },
            outsideClick(e) {
                if (!this.$store.state.sideBar || e.toElement.tagName === 'path' || e.toElement.tagName === 'svg') return;
                this.showHideSideBar();
            }
        }
    }
</script>

<style scoped>
    .sidebar {
        position: absolute;
        top: 0;
        right: 0;
        height: 100vh;
        line-height: 60px;
        width: 250px;
        display: flex;
        flex-direction: column;
        text-align: start;
        background-color: #e8afa6;
        color: white;
        box-shadow: inset 10px 0 26px -16px rgba(28,28,28,0.4);
        font-size: 24px;
        transform: translateX(0);
        transition: transform .4s ease-in-out;
        will-change: transform;
    }

    .routes > a > div{
        padding-left: 20px;
        box-shadow: inset 0 -1px 0 0 rgb(241, 199, 193);
        transition: .3s;
    }

    .routes > a > div:hover {
        background-color: #ffffff1f;
    }

    .routes a.active > div {
        background-color: #ffffff1f;
    }

    .routes > a {
        color: white;
    }
</style>