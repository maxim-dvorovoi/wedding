<template>
    <div class="header" :class="[{'with-bg': withBg}]">
        <div class="content-center">
            <router-link to="/" @click.native="scrollToTag('home')">
                <div class="logo">
                    <div class="custom-logo">M & A</div>
                </div>
            </router-link>

            <div class="routes">
                <svg
                    @click="showHideSideBar()"
                    :class="'burger' + ($store.state.sideBar ? ' active' : '')"
                    viewBox="0 0 100 100" width="45"
                >
                    <path
                        class="line top"
                        d="m 70,33 h -40 c -6.5909,0 -7.763966,-4.501509 -7.763966,-7.511428 0,-4.721448 3.376452,-9.583771 13.876919,-9.583771 14.786182,0 11.409257,14.896182 9.596449,21.970818 -1.812808,7.074636 -15.709402,12.124381 -15.709402,12.124381"
                    />
                    <path
                        class="line middle"
                        d="m 30,50 h 40"
                    />
                    <path
                        class="line bottom"
                        d="m 70,67 h -40 c -6.5909,0 -7.763966,4.501509 -7.763966,7.511428 0,4.721448 3.376452,9.583771 13.876919,9.583771 14.786182,0 11.409257,-14.896182 9.596449,-21.970818 -1.812808,-7.074636 -15.709402,-12.124381 -15.709402,-12.124381"
                    />
                </svg>
                <span>
                    <router-link to="/" @click.native="scrollToTag('aboutUs')">
                        О нас
                    </router-link>
                    <router-link to="/" @click.native="scrollToTag('program')">
                        Программа
                    </router-link>
                    <router-link to="/" @click.native="scrollToTag('place')">
                        Место проведения
                    </router-link>
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .header {
        position: sticky;
        top: 0;
        background-color: transparent;
        height: 80px;
        line-height: 80px;
        z-index: 2000;
        transition: background-color .5s, box-shadow .5s;
    }

    .header.with-bg {
        background-color: white;
        -webkit-box-shadow: 0 -2px 10px 0 rgba(0,0,0,0.3);
        -moz-box-shadow: 0 -2px 10px 0 rgba(0,0,0,0.3);
        box-shadow: 0 -2px 10px 0 rgba(0,0,0,0.3);
    }

    .logo {
        display: inline-block;
        font-size: 30px;
        color: rgb(228, 161, 152);
    }

    .custom-logo {
        font-family: 'Tangerine', cursive;
        font-weight: 700;
        font-size: 40px;
    }

    .routes {
        float: right;
    }

    .routes > span {
        margin-left: 15px;
    }

    .routes > span > a {
        padding: 3px;
        margin-left: 5px;
        color: rgb(228, 161, 152);
        transition: .3s;
        font-size: 25px;
    }

    .routes > span > a:hover {
        box-shadow: inset 0 -1px 0 0 rgb(228, 161, 152);
    }

    .routes a.active {
        box-shadow: inset 0 -1px 0 0 rgb(228, 161, 152);
    }

    .burger {
        display: none;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: transform 400ms;
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
        vertical-align: middle;
        position: relative;
        left: 10px;
    }

    .line {
        fill: none;
        transition: stroke-dasharray 400ms, stroke-dashoffset 400ms;
        stroke: rgb(228, 161, 152);
        stroke-width: 5.5;
        stroke-linecap: round;
    }

    .burger .top {
        stroke-dasharray: 40 121;
    }

    .burger .bottom {
        stroke-dasharray: 40 121;
    }

    .burger.active .top {
        stroke-dashoffset: -102px;
    }

    .burger.active .bottom {
        stroke-dashoffset: -102px;
    }

    @media (max-width: 1024px) {
        .header {
            height: 60px;
            line-height: 60px;
        }

        .burger {
            display: inline-block;
        }

        .routes > span {
            display: none;
        }
    }
</style>

<script>
    export default {
        name: 'Header',
        data() {
            return {
                withBg: false,
            }
        },
        methods: {
            onScroll() {
                if (this.$store.state.sideBar) return;
                if (document.body.style.top && document.body.style.position) {
                    return this.withBg = true;
                }

                this.withBg = this.scrollTop() > 30;
            }
        },
        async mounted() {
            window.addEventListener('scroll', this.onScroll)
        }
    }
</script>
