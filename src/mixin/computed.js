export default {
    app() {
        return this.$root.$children[0];
    },
    token() {
        return this.getCookie('token');
    },
    apiUrl() {
        if (process.env.NODE_ENV === 'development') {
            return 'http://localhost/projects/seo_dashboard/public/api.php';
        } else {
            return 'https://seo-admin.herokuapp.com/api.php';
        }
    }
};