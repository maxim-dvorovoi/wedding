<template>
    <span class="pointer" @click="sortTable(sort, !sortRev)">
        <slot></slot>
        <i
            v-if="app.query.sort == sort || (app.query.sort && app.query.sort.substr(1) == sort)"
            :class="'fa-fw fas fa-angle-' + ((app.query.sortDesc == 1 || app.query.sort.startsWith('-')) ? 'down' : 'up')"
        ></i>
    </span>
</template>

<script>
    export default {
        name: "Sort",
        props: ['sort', 'sortRev'],
        methods: {
            sortTable(col, isReverse = false) {
                let query;
                let sort = col;
                let querySort = this.app.query.sort;

                if (querySort && querySort.startsWith('-')) {
                    querySort = querySort.substr(1);
                }
                if (col === querySort) {
                    if (!this.app.query.sort.startsWith('-')) {
                        sort = '-' + sort;
                    }
                } else {
                    sort = isReverse ? sort : '-' + sort;
                }

                query = {...this.app.query, sort};
                this.$router.push({query});
            }
        }
    }
</script>

<style scoped>

</style>