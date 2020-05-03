export default {
    app() {
        return this.$root.$children[0];
    },
    token() {
        return this.getCookie('token');
    },
    apiUrl() {
        return 'http://localhost/projects/seo_dashboard/public/index.php/api';
        //return 'http://seoadmin.zzz.com.ua/api.php';
    }
};