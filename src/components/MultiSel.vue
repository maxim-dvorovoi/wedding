<template>
    <div class="multisel" :class="[{'is-disabled': disabled}]" v-mousedown-outside="hideItems">
        <span v-if="!disabled && !noClear && value" class="multisel-clear" @click="clear">
            <i class="fas fa-times"></i>
        </span>
        <i v-else class="multisel-arrow fa fa-caret-down"></i>
        <input type="text" class="multisel-val-tf" :value="tfText" @focus="showItems" :disabled="disabled" :placeholder="placeholder" readonly>
        <transition name="fade-down">
            <div v-show="itemsShown" ref="itemsBox" :class="'multisel-dd multisel-dd-' + ddPos">
                <div class="multisel-search-box">
                    <input
                        type="text"
                        class="multisel-search-tf"
                        v-model="searchVal"
                        ref="searchTf"
                        @keydown.esc="hideItems"
                        @keydown.enter="onEnterAction"
                        placeholder="Search"
                    >
                </div>
                <div>
                    <div class="multisel-dd-inner" ref="scrollableBox">
                        <div class="multisel-presets" v-if="presets && !searchVal">
                            <div v-for="preset in presets" :key="preset.key">
                                <span class="link" @click="choosePreset($event, preset)">{{ preset.text || preset.val }}</span>
                            </div>
                        </div>
                        <div v-if="withNot" class="multisel-invert-box">
                            <label class="nowrap chb">
                                <input
                                        type="checkbox"
                                        :checked="inverted"
                                        @click="setInverted(!inverted)"
                                >
                                <span>Not</span>
                            </label>
                        </div>
                        <div
                                v-for="(item, index) in filteredItems"
                                :key="item.key"
                                :class="['multisel-dd-item', item.className]"
                                :ref="'item' + item.key"
                        >
                            <label class="nowrap chb">
                                <input
                                        type="checkbox"
                                        :checked="!!checkedKeysMap[item.key]"
                                        @click="check($event, item, index)"
                                >
                                <span v-if="item.html" v-html="item.html"></span>
                                <span v-else>{{ item.val || '&nbsp;' }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="multisel-bottom clearfix">
                        <span class="left link" @click="checkAll">
                            <i class="fa fa-check"></i>
                            <span>Check all</span>
                        </span>
                        <span class="right link" @click="apply">
                            <i class="fa fa-save"></i>
                            <span>
                                Apply
                                <span v-if="checkedItemsCnt">({{ checkedItemsCnt }})</span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "MultiSel",
        data() {
            return {
                itemsShown: false,
                itemsChbLastIndex: null,
                isItemsCheckedAll: false,
                inverted: false,
                movingFastStep: 10,
                checkedKeysMap: {},
                stickyCheckedKeysMap: {},
                ddPos: null,
                searchVal: '',
                tfText: ''
            };
        },
        props: ['value', 'items', 'with-not', 'presets', 'no-sort', 'debug', 'defaultShow', 'no-clear', 'disabled', 'placeholder'],
        computed: {
            itemsWithSort() {
                let items = [...this.items].map((item, i) => {
                    if (item.sortVal == null) {
                        item.sortVal = i;
                    }
                    return item;
                });
                return items;
            },
            filteredItems() {
                if (!this.items) return this.items;
                let items = this.itemsWithSort.filter(this.checkItemMatch);

                items = items.sort((a, b) => {
                    let aChecked = this.stickyCheckedKeysMap[a.key];
                    let bChecked = this.stickyCheckedKeysMap[b.key];

                    if (!aChecked && bChecked) return 1;
                    if (aChecked && !bChecked) return -1;

                    if (a.sortVal > b.sortVal) return 1;
                    if (a.sortVal < b.sortVal) return -1;

                    return 0;
                });

                return items;
            },
            checkedItemsCnt() {
                return Object.keys(this.checkedKeysMap).length;
            }
        },
        methods: {
            showItems() {
                let winWidth = window.innerWidth;

                this.itemsShown = true;
                this.searchVal = '';
                this.ddPos = 'left';
                this.itemsChbLastIndex = null;

                this.stickyCheckedKeysMap = {...this.checkedKeysMap};

                this.$nextTick(() => {
                    this.$refs.searchTf.focus();
                    let boxRight = this.$refs.itemsBox.getBoundingClientRect().right;
                    let overlay = boxRight - winWidth + 10;
                    this.ddPos = overlay < 0 ? 'left' : 'right';

                    this.tryFocusSearch();
                    this.$refs.scrollableBox.scrollTop = 0;
                });
            },
            hideItems() {
                this.itemsShown = false;
                this.updateVal();
            },
            tryFocusSearch() {
                if (this.$refs.searchTf && this.app.win.width >= this.app.win.LG) {
                    this.$refs.searchTf.focus();
                }
            },
            updateTfText() {
                let texts = Object.keys(this.checkedKeysMap).map(key => {
                    let item = this.checkedKeysMap[key];
                    return item.displayVal || item.val;
                });
                this.tfText = (this.inverted ? this.ii('NOT') + ': ' : '') + texts.join(', ');
            },
            checkItemMatch(item) {
                if (!this.searchVal) return true;

                if (!item) return false;

                let regex = new RegExp(this.escapeRegex(this.searchVal), 'i');
                return regex.test(item.sVal || item.val);
            },
            setInverted(isInv) {
                this.inverted = isInv;
                this.tryFocusSearch();
            },
            check(e, item, index) {
                let checked = e.target.checked;

                if (e.shiftKey && this.itemsChbLastIndex != null) {
                    let start = Math.min(index, this.itemsChbLastIndex);
                    let end = Math.max(index, this.itemsChbLastIndex) + 1;

                    this.filteredItems.slice(start, end).forEach(item => {
                        if (e.target.checked) {
                            this.$set(this.checkedKeysMap, item.key, item);
                        } else {
                            this.$delete(this.checkedKeysMap, item.key);
                        }
                    });
                } else if (checked) {
                    this.$set(this.checkedKeysMap, item.key, item);
                } else {
                    this.$delete(this.checkedKeysMap, item.key);
                }

                this.itemsChbLastIndex = index;
                this.tryFocusSearch();
            },
            updateModel() {
                this.updateTfText();

                let keys = Object.keys(this.checkedKeysMap);
                let val;
                if (keys.length) {
                    val = (this.inverted ? 'n_' : '') + keys.join(',');
                } else {
                    val = '';
                }

                this.$emit('input', val);
                this.$emit('change', {isTrusted: true});
            },
            updateVal() {
                if (!this.value || !this.items) {
                    this.inverted = false;
                    this.checkedKeysMap = {};
                    return;
                }
                let map = {};
                let val = this.value;
                if (val.indexOf('n_') === 0) {
                    val = val.slice(2);
                    this.inverted = true;
                } else {
                    this.inverted = false;
                }
                val.split(',').forEach(key => {
                    let item = this.items.filter(el => el.key == key)[0];
                    if (!item) return;

                    map[key] = item;
                });
                this.checkedKeysMap = map;
            },
            choosePreset(e, preset) {
                let keys = preset.key;
                if (e.ctrlKey) {
                    let checkedKeys = Object.keys(this.checkedKeysMap).join(',');
                    keys = (checkedKeys ? checkedKeys + ',' : '') + keys;
                    keys = keys.split(',').filter((v, i, a) => a.indexOf(v) === i).join(',');
                }

                let map = {};
                keys.split(',').forEach(key => {
                    let item = this.items.find(it => it.key == key);
                    if (item) {
                        map[key] = item;
                    }
                });
                this.checkedKeysMap = map;
                let filtredKeys = Object.keys(map).join(',');

                this.$emit('input', filtredKeys);
                this.$emit('change', {isTrusted: true});

                this.updateTfText();
                this.hideItems();
            },
            clear() {
                this.$emit('input', '');
                this.$emit('change', {isTrusted: true});

                this.hideItems();
                this.tfText = '';
            },
            checkAll() {
                let uncheckedExist = this.filteredItems.filter(item => !this.checkedKeysMap[item.key]).length;

                this.filteredItems.forEach(item => {
                    if (uncheckedExist) {
                        this.$set(this.checkedKeysMap, item.key, item);
                    } else {
                        this.$delete(this.checkedKeysMap, item.key);
                    }
                });

                this.itemsChbLastIndex = null;
                this.tryFocusSearch();
            },
            onEnterAction() {
                if (this.isObjectEmpty(this.checkedKeysMap) && !this.isObjectEmpty(this.filteredItems) && this.searchVal) {
                    this.$set(this.checkedKeysMap, this.filteredItems[0].key, this.filteredItems[0]);
                }

                this.apply();
            },
            apply() {
                this.updateModel();
                this.hideItems();
            }
        },
        watch: {
            value() {
                this.updateVal();
            },
            items() {
                this.updateVal();
                this.updateTfText();
            },
            searchVal() {
                this.itemsChbLastIndex = null;
            }
        },
        mounted() {
            this.itemsShown = this.defaultShow;
            this.updateVal();
            this.updateTfText();
        },
        created() {
            this.updateVal();
            this.updateTfText();
        }
    }
</script>

<style scoped>
    .multisel {
        display: inline-block;
        position: relative;
        background: #fff;
    }

    .multisel * {
        text-align: left;
    }

    .multisel-clear {
        position: absolute;
        top: 6px;
        right: 7px;
        cursor: pointer;
        font-size: 14px;
        z-index: 1;
        color: #3e7162;
    }

    .multisel-arrow {
        position: absolute;
        top: 10px;
        right: 7px;
        font-size: 14px;
        z-index: 1;
        color: #3e3e3e;
    }

    .multisel-val-tf {
        position: relative;
        padding: 5px 20px 5px 5px;
        width: 100%;
        line-height: 1.5;
        color: #3e3e3e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #b8b8b8;
        box-sizing: border-box;
        border-radius: 5px;
        font-size: 14px;
    }

    .multisel-val-tf:focus {
        outline: none;
    }

    .multisel-search-box {
        padding: 5px;
    }

    .multisel-search-tf {
        width: 100%;
        border: 1px solid #b8b8b8;
        border-radius: 5px;
        box-sizing: border-box;
        padding: 4px;
    }

    .multisel-search-tf:focus {
        border-color: #3e7162;
        outline: none;
    }

    .multisel-dd {
        position: absolute;
        margin-top: 5px;
        min-width: 100%;
        background: #fff;
        border: 1px solid #9a9a9a;
        border-radius: 5px;
        box-sizing: border-box;
        z-index: 10;
    }

    .multisel-dd-inner {
        max-height: 300px;
        overflow-y: auto;
    }

    .multisel-presets {
        padding: 0 5px;
    }

    .multisel-presets div span {
        font-weight: 500;
        font-size: 14px;
    }

    .multisel-invert-box {
        padding: 2px 4px 10px 4px;
    }

    .multisel-invert-box span {
        font-weight: bold;
        text-transform: uppercase;
    }

    .multisel-dd-item {
        padding: 3px 0;
        cursor: pointer;
        white-space: nowrap;
        font-size: 14px;
    }

    .multisel-dd-item:hover {
        background: #3e7162;
        color: white;
    }

    .multisel-dd-item label {
        display: block !important;
        font-weight: 400;
        cursor: pointer;
    }

    .multisel-dd-item label.chb input[type="checkbox"]{
        margin: 0 5px;
    }

    .multisel-dd-item span {
        opacity: 1;
    }

    .multisel-item-sectext {
        color: #777;
        font-size: 13px;
        font-style: italic;
    }

    .multisel-dd-item span span {
        color: #fff;
    }

    .multisel-dd-item span input[type="checkbox"] {
        margin: 0 3px 0 0;
    }

    .multisel-dd-sep {
        height: 6px;
    }

    .multisel-bottom {
        padding: 5px;
        font-size: 14px;
        min-width: 170px;
    }

    .multisel-bottom .link i {
        margin-right: 4px;
    }

    .is-disabled {
        background-color: inherit !important;
        opacity: .7 !important;
    }

    @media (max-width: 400px) {
        .multisel-dd-inner {
            max-width: 300px;
        }
    }
</style>