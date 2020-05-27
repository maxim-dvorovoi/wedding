import Cookies from 'js-cookie';

let methods = {
    setLoading(st) {
        this.app.contentLoading = st;
    },
    getCookie(key) {
        return Cookies.get(key);
    },
    setCookie(key, value, params = {}) {
        return Cookies.set(key, value, params);
    },
    removeCookie(key, params = {}) {
        return Cookies.remove(key, params);
    },
    refreshQuery(opts = {}) {
        let query = this.app.query;
        for (let i in query) {
            if (!query.hasOwnProperty(i)) continue;
            if (query[i] === '' || query[i] == null) {
                delete query[i];
            }
        }

        let hash = opts.keepHash ? this.app.hash : null;

        this.navigate({ query, hash });
    },
    navigate(opts, isReplace = false) {
        let query = opts.query || {};

        let method = isReplace ? 'replace' : 'push';
        this.$router[method]({ ...opts, query }).catch(err => {
            if (err.name !== 'NavigationDuplicated') {
                throw err;
            }
        });
    },
    editQueryParams(params, isReplace = false) {
        let query = {...this.app.query, ...params};
        this.navigate({query}, isReplace);
    },
    sleep(timeout) {
        return new Promise(resolve => {
            setTimeout(resolve, timeout);
        });
    },
    escapeRegex(str, delimiter) {
        let regex = new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g');
        return (str + '').replace(regex, '\\$&');
    },
    escapeHtml(html) {
        if (!html) return '';
        return html
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    },
    unescapeHtml(html) {
        if (!html) return '';
        return html
            .replace(/&amp;/g, "&")
            .replace(/&lt;/g, "<")
            .replace(/&gt;/g, ">")
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'");
    },
    stripTags(html) {
        if (!html) return '';
        return html.replace(/<.*?>/g, '');
    },
    newLineToBr(raw, maxNewLines = null) {
        let str = String(raw || '').replace(/\r?\n/g, '<br>');

        maxNewLines = parseInt(maxNewLines);
        if (maxNewLines) {
            let re = new RegExp('((<br>){' + maxNewLines + '})(<br>)+');
            str = str.replace(re, '$1');
        }
        return str;
    },
    getDomainFromUrl(url, keepWww = false) {
        let domain = String(url).trim().toLowerCase().replace(/^https?:\/\//i, '').replace(/\/.*/, '');
        if (!keepWww) {
            domain = domain.replace(/^www\./i, '');
        }
        return domain;
    },
    getFullUrl(url) {
        if (/^https?:\/\//i.test(url)) return url;
        if (/^\/\//.test(url)) return 'http:' + url;
        return 'http://' + url;
    },
    wrapLinks(html) {
        if (!html) return html;

        html = this.escapeHtml(html);
        html = html.replace(/(https?:\/\/[^\s"'(),]+)[^\.\s,]/gi, '<a href="$&" target="_blank" rel="noopener noreferrer">$&</a>');
        return html;
    },
    isObjectEmpty(obj) {
        if (!obj) return true;
        for (let i in obj) return false;
        return true;
    },
    parseJson(json, showError = false) {
        try {
            return JSON.parse(json);
        } catch (err) {
            if (showError) console.log(err);
            return null;
        }
    },
    toJson(data, opts = {}) {
        if (opts.pretty) return JSON.stringify(data, '', 4);
        return JSON.stringify(data);
    },
    beautifyJson(json) {
        return this.toJson(this.parseJson(json), {pretty: true});
    },
    inArray(item, array) {
        if (!this.isArray(array)) return false;
        return array.indexOf(item) !== -1;
    },
    isArray(data) {
        return data instanceof Array;
    },
    getWinWidth() {
        return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    },
    getWinHeight() {
        return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    },
    localSetItem(key, value) {
        try {
            localStorage.setItem(key, value);
        } catch (e) {
            window.console.error("Error writing to the storage for key = " + key +
                "! (this is expected in private mode in Safari)");
        }
    },
    localGetItem(key) {
        try {
            return localStorage.getItem(key);
        } catch (e) {
            window.console.error("Error reading to the storage for key = " + key +
                "! (this is expected in private mode in Safari)");
        }
    },
    localRemoveItem(key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            window.console.error("Error removing to the storage for key = " + key +
                "! (this is expected in private mode in Safari)");
        }
    },
    round(val) {
        return Math.round(val);
    },
    getHumanTime(sec) {
        if (!sec) return '';
        sec = Math.round(sec);

        let s = sec % 60;

        let min = Math.floor(sec / 60);
        let m = min % 60;

        let hr = Math.floor(min / 60);
        let h = hr % 24;
        let d = Math.floor(hr / 24);

        let str = s + 's';
        if (m) {
            str = m + 'm ' + str;
        }
        if (h) {
            str = h + 'h ' + str;
        }
        if (d) {
            str = d + 'd ' + str;
        }
        return str;
    },
    scrollTop() {
        return window.scrollY != null ? window.scrollY : window.pageYOffset;
    },
    animate(options) {
        let start = performance.now();

        requestAnimationFrame(function animate(time) {
            let timeFraction = (time - start) / options.duration;
            if (timeFraction > 1) timeFraction = 1;

            let progress = options.timing(timeFraction);
            options.draw(progress);

            if (timeFraction < 1) requestAnimationFrame(animate);
        });
    },
    makeEaseInOut(timing) {
        return (timeFraction) => {
            if (timeFraction < .5) return timing(2 * timeFraction) / 2;

            return (2 - timing(2 * (1 - timeFraction))) / 2;
        }
    },
    quad(timeFraction) {
        return Math.pow(timeFraction, 2);
    },
    scrollToTag(id) {
        this.disableScroll();
        let clientY = this.scrollTop();
        let tagY = document.getElementById(id).offsetTop - 10;
        if (window.innerWidth > 962) tagY = tagY - 40;

        this.animate({
            duration: 1000,
            timing: this.makeEaseInOut(this.quad),
            draw: (progress) => {
                window.scrollTo({top: clientY - (clientY - tagY) * progress});
                if (progress === 1) this.enableScroll();
            }
        });
    },
    disableScroll() {
        document.addEventListener('DOMMouseScroll', this.preventDefault, false);
        document.addEventListener('wheel', this.preventDefault, {passive: false});
        document.addEventListener('mousewheel', this.preventDefault, {passive: false});
        document.addEventListener('onmousewheel', this.preventDefault, {passive: false});
        document.addEventListener('MozMousePixelScroll', this.preventDefault, {passive: false});

        document.addEventListener('onkeydown', this.preventDefaultForScrollKeys, {passive: false});
        document.addEventListener('touchmove', this.preventDefault, {passive: false});
    },
    enableScroll() {
        document.removeEventListener('DOMMouseScroll', this.preventDefault, false);
        document.removeEventListener('wheel', this.preventDefault, {passive: false});
        document.removeEventListener('mousewheel', this.preventDefault, {passive: false});
        document.removeEventListener('onmousewheel', this.preventDefault, {passive: false});
        document.removeEventListener('MozMousePixelScroll', this.preventDefault, {passive: false});

        document.removeEventListener('onkeydown', this.preventDefaultForScrollKeys, {passive: false});
        document.removeEventListener('touchmove', this.preventDefault, {passive: false});
    },
    preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault) e.preventDefault();

        e.returnValue = false;
    },
    preventDefaultForScrollKeys(e) {
        let keys = {37: 1, 38: 1, 39: 1, 40: 1};
        if (!keys[e.keyCode]) return;

        this.preventDefault(e);
        return false;
    }
};

export default methods;