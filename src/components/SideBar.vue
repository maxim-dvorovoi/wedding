<template>
    <div class="sidebar" v-mousedown-outside="outsideClick" id="sidebar">
        <div class="content">
            <div class="routes">
                <router-link to="/" @click.native="scrollTo('home')">
                    <div>Главная</div>
                </router-link>
                <router-link to="/" @click.native="scrollTo('aboutUs')">
                    <div>О нас</div>
                </router-link>
                <router-link to="/" @click.native="scrollTo('photo')">
                    <div>Фото</div>
                </router-link>
                <router-link to="/" @click.native="scrollTo('program')">
                    <div>Программа</div>
                </router-link>
                <router-link to="/" @click.native="scrollTo('place')">
                    <div>Место проведения</div>
                </router-link>
            </div>
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
                if (!this.$store.state.sideBar) return;
                if (e.path && e.path.filter(i => i && i.classList && i.classList.contains('burger')).length) return;

                this.showHideSideBar();
            }
        }
    }
</script>

<style scoped>
    .sidebar {
        position: fixed;
        top: 0;
        right: 0;
        line-height: 60px;
        width: 250px;
        height: 100%;
        display: flex;
        flex-direction: column;
        text-align: start;
        background-color: #e8afa6;
        border-left: 1px solid #efc1bb;
        box-sizing: border-box;
        color: white;
        font-size: 24px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .sidebar .content > div {
        width: 100%;
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

    .routes .category:before {

    }

    .routes > a {
        color: white;
    }
</style>