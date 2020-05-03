<template>
    <div class="header">
        <div class="content-center">
            <router-link :to="app.routeName === 'login' ? '/login' : '/'">
                <div class="logo">
                    <div class="custom-logo">
                        <div class="font25">
                            SEO
                            <i class="font20 fas fa-sort-amount-up-alt"></i>
                        </div>
                        <div class="font25">Admin</div>
                    </div>
                </div>
            </router-link>

            <div class="routes" v-if="app.routeName !== 'login'">
                <svg
                    @click="$store.state.sideBar = !$store.state.sideBar"
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
                    <router-link to="/" :class="[{'active': app.routeName === 'home'}]">
                        Home
                    </router-link>
                </span>
                <span>
                    <router-link to="/auto-checker" :class="[{'active': app.routeName === 'autoChecker'}]">
                        Auto checker
                    </router-link>
                </span>
                <span>
                    <router-link to="/login">Logout</router-link>
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .header {
        position: sticky;
        top: 0;
        background-color: white;
        height: 80px;
        line-height: 80px;
        -webkit-box-shadow: 0px -2px 10px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px -2px 10px 0px rgba(0,0,0,0.75);
        box-shadow: 0px -2px 10px 0px rgba(0,0,0,0.75);
        z-index: 1000;
    }

    .logo {
        display: inline-block;
        font-size: 30px;
        color: rgba(54,99,86,1);
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
        color: black;
        transition: .3s;
    }

    .routes > span > a:hover {
        box-shadow: inset 0 -2px 0 0 rgba(54,99,86,1);
    }

    .routes a.active {
        box-shadow: inset 0 -2px 0 0 rgba(54,99,86,1);
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
        stroke: rgba(54,99,86,1);
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

    .custom-logo {
        padding: 15px 0;
    }

    .custom-logo div {
        font-size: 25px;
        line-height: 25px;
    }

    .custom-logo i {
        font-size: 20px;
        line-height: 20px;
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

        .custom-logo {
            padding: 10px 0;
        }

        .custom-logo div {
            font-size: 20px;
            line-height: 20px;
        }

        .custom-logo i {
            font-size: 16px;
            line-height: 16px;
        }
    }
</style>

<script>
    export default {
        name: 'Header'
    }
</script>
