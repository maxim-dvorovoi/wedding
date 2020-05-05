<template>
    
</template>

<script>
    export default {
        name: "admin",
        data() {
            return {
                init: false,
            }
        },
        methods: {

        },
        async mounted() {
            this.setLoading(true);
            let ok = await this.checkAuth();
            if (!ok) return;

            this.setLoading(false);
            this.init = true;
        }
    }
</script>

<style scoped>

</style>