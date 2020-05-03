<template>
    <div class="footer">
        <div class="content-center">
            <div class="flex-between">
                <div>
                    <a href="https://ua.linkedin.com/in/maxim-dvorovoi" target="_blank">© 2020 Maxim Dvorovoi</a>
                </div>
                <div>
                    <router-link :to="app.routeName === 'login' ? '/login' : '/'">SEO Admin</router-link>
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
        font-weight: 300;
        flex: 0 0 auto;
        background-color: rgba(54,99,86,1);
        height: 60px;
    }

    .flex-between {
        display: flex;
        justify-content: space-between;
        height: 60px;
        align-items: center;
    }

    a {
        color: white !important;
        transition: .3s;
    }

    a:hover {
        opacity: .7;
    }
</style>