<template>
    <div class="richsel" :class="[disabled ? 'is-disabled' : '']" :data-val="value" v-mousedown-outside="hideSuggestions">
        <div>
            <i class="richsel-arrow fa fa-caret-down"></i>
            <input type="text" class="richsel-val-tf" ref="valTf" :value="tfText" :placeholder="placeholder" :disabled="disabled" @focus="showSuggestions">
            <transition name="fade-down">
                <div ref="suggestBox" :class="['richsel-dd', 'richsel-dd-' + ddPos]" v-show="suggestionsShown">
                    <div class="richsel-search-box" v-if="!searchHidden">
                        <input
                            type="text"
                            class="richsel-search-tf"
                            v-model="searchVal"
                            ref="searchTf"
                            @keydown.down.prevent="moveDown"
                            @keydown.up.prevent="moveUp"
                            @keydown.page-up.prevent="moveUpFast"
                            @keydown.page-down.prevent="moveDownFast"
                            @keydown.enter.prevent="choose"
                            @keydown.esc="hideSuggestions"
                            @input="isRemote ? loadItems() : null"
                            :placeholder="searchPlaceholder || 'Search'"
                        >
                    </div>
                    <div class="richsel-dd-items" ref="itemsBox">
                        <div
                            v-for="(item, index) in filteredItems"
                            :class="['richsel-dd-item', {'richsel-hilited': (index === hiliteIndex)}]"
                            :ref="'item' + item.key"
                            :title="item.title"
                            :style="itemStyle"
                            @mouseover="hiliteIndex = index"
                            @click="choose"
                        >
                            <slot name="item" :item="item">
                                <div v-if="item.html" v-html="item.html"></div>
                                <div v-else>{{ item.val || '&nbsp;' }}</div>
                            </slot>
                        </div>
                    </div>
                    <div v-if="isRemote && !searchVal && selectVal" class="richsel-bottom">
                        <span class="link" @click="clear">
                            <i class="fas fa-times"></i>
                            <span>Clear</span>
                        </span>
                    </div>
                    <em v-if="noData" class="richsel-alter-items no-data-item">No data</em>
                    <div v-if="dataLoading" class="richsel-alter-items loding-item">
                        <i class="fas fa-spinner fast-spin"></i>
                        Loading...
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "RichSel",
        data() {
            return {
                suggestionsShown: false,
                searchVal: '',
                hiliteIndex: 0,
                movingFastStep: 10,
                ddPos: null,
                selectVal: null,
                resItems: [],
                time: new Date().getTime(),
                source: null,
                timer: null,
                dataLoading: false,
                noData: false,
                defaultDelay: 300
            };
        },
        props: [
            'value', 'items', 'items-url', 'no-sort', 'key-ignore-case', 'no-empty',
            'placeholder', 'search-placeholder', 'default-text', 'delay', 'custom-handler',
            'max-items', 'no-filter', 'empty-val', 'disabled', 'item-max-width', 'search-hidden'
        ],
        computed: {
            itemStyle() {
                if (this.itemMaxWidth) {
                    let maxWidth = this.itemMaxWidth;
                    if (!isNaN(maxWidth)) {
                        maxWidth += 'px';
                    }
                    return {maxWidth};
                }

                return '';
            },
            isRemote() {
                return !this.items && this.itemsUrl;
            },
            tfText() {
                let item = this.getItemByValue();
                if (!item) return this.defaultText || this.selectVal || '';
                return item.displayVal || item.val;
            },
            shouldSort() {
                let elemsWithSort = this.resItems.filter(it => it && it.sortVal != null);
                return !!elemsWithSort.length;
            },
            sortedItems() {
                if (!this.resItems || this.noSort || !this.shouldSort) return this.resItems;

                let items = this.resItems.sort((a, b) => {
                    if (!a || !b) return 0;

                    if (a.sortVal == null && b.sortVal == null) return 0;

                    if (a.sortVal > b.sortVal) return 1;
                    if (a.sortVal < b.sortVal) return -1;

                    return 0;
                });

                items = items.filter(i => i);

                return items;
            },
            filteredItems() {
                if (!this.sortedItems || !this.sortedItems.length) return this.sortedItems;
                let items = this.sortedItems.filter(this.checkItemMatch);
                if (this.searchVal || this.noEmpty) return items;

                let key = (this.emptyVal !== void 0 ? this.emptyVal : null);
                let val = '';
                let emptyItem = {key, val};
                return [emptyItem, ...items];
            },
            trueDelay() {
                return parseInt(this.delay) || this.defaultDelay;
            }
        },
        methods: {
            showSuggestions() {
                if (this.isRemote) {
                    this.resItems = [];
                }
                this.suggestionsShown = true;
                this.ddPos = 'left';

                let item = this.getItemByValue();
                let index = this.filteredItems.indexOf(item);
                if (index === -1) {
                    index = 0;
                }
                this.hiliteIndex = index;

                this.$nextTick(() => {
                    this.tryScroll();
                    this.$refs.valTf.blur();

                    if (!this.searchHidden) {
                        this.$refs.searchTf.focus();
                    }

                    let boxRight = this.$refs.suggestBox.getBoundingClientRect().right;
                    let overlay = boxRight - window.innerWidth + 50;
                    this.ddPos = overlay < 0 ? 'left' : 'right';
                });
            },
            hideSuggestions() {
                this.suggestionsShown = false;
                this.searchVal = '';
            },
            getItemByValue() {
                if (!this.resItems) return;

                let key = this.selectVal || -1;
                if (this.keyIgnoreCase && key !== -1) {
                    key = key.toLowerCase();
                    return this.resItems.filter(item => item && String(item.key).toLowerCase() === key)[0];
                }
                return this.resItems.filter(item => item && item.key == key)[0];
            },
            getHilitedItem() {
                return this.filteredItems[this.hiliteIndex];
            },
            checkItemMatch(item) {
                if (this.noFilter) return true;

                let sVal = (this.searchVal || '').trim();
                if (!sVal) return true;

                if (!item) return false;

                let regex = new RegExp(this.escapeRegex(sVal), 'i');
                return regex.test(item.sVal || item.val)
            },
            moveUp(opt) {
                if (--this.hiliteIndex < 0) {
                    this.hiliteIndex = this.filteredItems.length - 1;
                }
                if (!opt.repeated && !this.getHilitedItem()) return this.moveUp({repeated: true});
                this.$nextTick(this.tryScroll);
            },
            moveDown(opt) {
                if (++this.hiliteIndex >= this.filteredItems.length) {
                    this.hiliteIndex = 0;
                }
                if (!opt.repeated && !this.getHilitedItem()) return this.moveDown({repeated: true});
                this.$nextTick(this.tryScroll);
            },
            moveUpFast(opt) {
                this.hiliteIndex -= this.movingFastStep;
                if (this.hiliteIndex < 0) {
                    this.hiliteIndex = 0;
                }
                if (!opt.repeated && !this.getHilitedItem()) return this.moveUp({repeated: true});
                this.$nextTick(this.tryScroll);
            },
            moveDownFast(opt) {
                this.hiliteIndex += this.movingFastStep;
                if (this.hiliteIndex >= this.filteredItems.length) {
                    this.hiliteIndex = this.filteredItems.length - 1;
                }
                if (!opt.repeated && !this.getHilitedItem()) return this.moveDown({repeated: true});
                this.$nextTick(this.tryScroll);
            },
            choose() {
                let item = this.filteredItems[this.hiliteIndex];
                if (!item) return;

                this.hideSuggestions();

                this.selectVal = item.key;

                this.$emit('input', item.key);
                this.$emit('change', {isTrusted: true});
            },
            select() {
                this.$emit('input', this.selectVal);
                this.$emit('change', {isTrusted: true});
            },
            clear() {
                this.selectVal = '';
                this.$emit('input', '');
                this.$emit('change', {isTrusted: true});
                this.hideSuggestions();
            },
            tryScroll() {
                let item = this.filteredItems[this.hiliteIndex];
                if (!item) return;

                let itemNodes = this.$refs['item' + item.key];
                if (!itemNodes || !itemNodes[0]) return;

                let itemNode = itemNodes[0];
                let itemPos = itemNode.getBoundingClientRect();

                let itemsBox = this.$refs.itemsBox;
                let itemsBoxPos = itemsBox.getBoundingClientRect();

                let extraDistBottom = itemPos.bottom - itemsBoxPos.bottom;
                if (extraDistBottom > 0) {
                    itemsBox.scrollTop += extraDistBottom;
                }

                let extraDistTop = itemsBoxPos.top - itemPos.top;
                if (extraDistTop > 0) {
                    itemsBox.scrollTop -= extraDistTop;
                }
            },
            loadItems() {
                this.dataLoading = false;
                this.noData = false;
                this.resItems = [];
                if (this.timer) clearTimeout(this.timer);
                if (!this.searchVal) {
                    this.source.cancel('');
                    return;
                }
                if (this.timer) clearTimeout(this.timer);

                this.timer = setTimeout(() => {
                    if (!this.source) {
                        this.source = axios.CancelToken.source();
                    }
                    else {
                        this.source.cancel('');
                    }

                    this.source = axios.CancelToken.source();
                    this.noData = false;
                    this.dataLoading = true;

                    let req = this.itemsUrl.indexOf('?') >= 0 ? '&q=' : '?q=';

                    axios.get(this.apiUrl + this.itemsUrl + req + this.searchVal + '&token=' + this.token, {
                        cancelToken: this.source.token
                    })
                        .then(data => {
                            if (!data) return;
                            if (!data.data.res || !data.data.res.items) {
                                this.noData = true;
                            } else {
                                let items = data.data.res.items.slice(0, this.maxItems || 10);
                                if (this.customHandler) {
                                    items = items.map(this.customHandler);
                                }

                                this.resItems = items;
                            }

                            this.dataLoading = false;
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }, this.trueDelay);
            },
            updateResItems() {
                this.resItems = this.items ? [...this.items] : [];
            },
        },
        watch: {
            searchVal() {
                this.hiliteIndex = 0;
            },
            value() {
                this.selectVal = this.value;
            },
            items() {
                this.updateResItems();
            }
        },
        created() {
            this.selectVal = this.value;
            this.updateResItems();
        }
    }
</script>

<style scoped>
    .richsel {
        display: inline-block;
        position: relative;
        background: #fff;
    }

    .richsel * {
        text-align: left;
    }

    .richsel-arrow {
        position: absolute;
        top: 10px;
        right: 7px;
        font-size: 14px;
        z-index: 1;
        color: #3e3e3e;
    }

    .richsel-val-tf {
        position: relative;
        padding: 5px 20px 5px 5px;
        width: 100%;
        line-height: 1.5;
        color: #3e3e3e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #b8b8b8;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 14px;
    }

    .richsel-val-tf:focus {
        outline: none;
    }

    .richsel-search-box {
        padding: 5px;
    }

    .richsel-dd {
        position: absolute;
        margin-top: 5px;
        min-width: 100%;
        background: #fff;
        border: 1px solid #b8b8b8;
        border-radius: 5px;
        box-sizing: border-box;
        overflow: hidden;
        z-index: 10;
    }

    .richsel-search-tf {
        width: 100% !important;
        border: 1px solid #b8b8b8;
        box-sizing: border-box;
        border-radius: 5px;
        padding: 5px;
    }

    .richsel-search-tf:focus {
        border-color: #3e7162;
        outline: none;
    }

    .richsel-dd-items {
        max-height: 300px;
        overflow-y: auto;
    }

    .richsel-dd-item {
        cursor: pointer;
        font-size: 14px;
        white-space: nowrap;
    }

    .richsel-dd-item > div {
        padding: 4px 6px;
    }

    .richsel-dd-item.richsel-hilited > div {
        color: #fff;
        background: #3e7162;
    }

    .richsel-dd-item.richsel-hilited > div span{
        color: #fff !important;
    }

    .richsel-dd-sep {
        height: 6px;
    }

    .richsel-alter-items {
        display: block;
        text-align: center;
        margin: 5px;
    }

    .richsel-bottom {
        padding: 1px 7px 4px 5px;
    }

    .richsel-bottom .link i {
        margin-right: 4px;
    }

    .is-disabled {
        background-color: inherit!important;
        opacity: .7!important;
    }

    @media (max-width: 400px) {
        .richsel-dd-items {
            max-width: 300px;
        }
    }
</style>