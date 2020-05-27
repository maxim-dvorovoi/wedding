<template>
    <div class="footer">
        <div class="content-center">
            <div class="flex-between">
                <div>
                    <a href="https://ua.linkedin.com/in/maxim-dvorovoi" target="_blank">© 2020 Maxim Dvorovoi</a>
                </div>
                <div>
                    <router-link to="/" @click.native="scrollToTag('home')">
                        <div class="custom-logo">M & A</div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Footer"
    }
</script>

<style scoped>
    .footer {
        color: white;
        font-size: 18px;
        font-weight: 300;
        flex: 0 0 auto;
        background-color: #4d6989;
        height: 80px;
    }

    .flex-between {
        display: flex;
        justify-content: space-between;
        height: 80px;
        align-items: center;
    }

    .custom-logo {
        font-family: 'Tangerine', cursive;
        font-weight: 700;
        font-size: 20px;
    }

    a {
        color: white !important;
        transition: .3s;
    }

    a:hover {
        opacity: .7;
    }
</style>