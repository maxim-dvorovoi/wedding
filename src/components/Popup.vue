<template>
    <transition name="fade">
        <div :class="['popup', cls]" @click="onClick" @mousedown="mouseDown = $event.target">
            <div class="popup-panel" :style="style" ref="popupBox">
                <div class="popup-header" @mousedown="onHeaderMouseDown" @touchstart="onHeaderTouchStart"
                     ref="popupHeader">
                    <div class="left">
                        <slot name="header"></slot>
                    </div>
                    <div class="right">
                        <span @click="close()" class="popup-close">
                            <i class="fas fa-times"></i>
                        </span>
                    </div>
                </div>
                <div class="popup-content-outer">
                    <div
                        ref="content"
                        :class="['popup-content', {'popup-scrollable': !noScroll}]"
                        :style="contentStyle"
                        @scroll="$emit('scroll', $event)"
                    >
                        <slot>empty popup</slot>
                    </div>
                </div>
                <div class="popup-footer">
                    <slot name="footer"></slot>
                    <div v-if="resize" class="popup-resizer-box" @mousedown="onResizeMouseDown"></div>
                </div>
            </div>
            <div class="overlaying-fix" v-if="overfix"></div>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "Popup",
        props: [
            'cls',
            'width',
            'content-min-height',
            'content-max-height',
            'scroll-to-bottom',
            'hard-close',
            'no-scroll',
            'resize'
        ],
        data() {
            return {
                dndStartX: 0,
                dndStartY: 0,
                offsetLeft: 0,
                offsetTop: 0,
                overfix : false,
                widthPopup : 0,
                heightPopup : 0,
                mouseDown: null
            };
        },
        computed: {
            style() {
                let style = {};

                if (this.offsetLeft || this.offsetTop) {
                    style.transform = 'translate(' + this.offsetLeft + 'px,' + this.offsetTop + 'px)';
                }
                if (this.width) {
                    style.width = isNaN(this.width) ? this.width : (this.width + 'px');
                    if (this.widthPopup > 0) style.width = this.widthPopup + 'px';
                    if (this.heightPopup > 0) this.$refs.content.style.minHeight = this.heightPopup + 'px';
                    if (this.heightPopup > 0) this.$refs.content.style.maxHeight = this.heightPopup + 'px';
                }
                return style;
            },
            contentStyle() {
                let style = {};
                if (this.contentMinHeight) {
                    style.minHeight = isNaN(this.contentMinHeight) ? this.contentMinHeight : this.contentMinHeight + 'px';
                }
                if (this.contentMaxHeight) {
                    style.maxHeight = isNaN(this.contentMaxHeight) ? this.contentMaxHeight : this.contentMaxHeight + 'px';
                }
                return style;
            }
        },
        methods: {
            onClick(e) {
                if (this.hardClose) return;
                if (e.target !== this.$el || this.mouseDown !== this.$el) return;
                this.close();
            },

            onHeaderMouseDown(e) {
                if (e.target !== e.currentTarget) return;

                e.preventDefault();

                this.dndStartX = e.clientX - this.offsetLeft;
                this.dndStartY = e.clientY - this.offsetTop;

                document.addEventListener('mousemove', this.onDocMouseMove);
                document.addEventListener('mouseup', this.unbindDocMouseUp);

                this.unbindDocTouchEnd();
            },
            onDocMouseMove(e) {
                this.offsetLeft = e.clientX - this.dndStartX;
                this.offsetTop = e.clientY - this.dndStartY;
            },
            unbindDocMouseUp() {
                document.removeEventListener('mousemove', this.onDocMouseMove);
                document.removeEventListener('mouseup', this.unbindDocMouseUp);
            },

            onResizeMouseDown(e) {
                if (!this.resize) return;
                this.overfix = true;

                e.preventDefault();

                document.addEventListener('mousemove', this.onResizeMouseMove);
                document.addEventListener('mouseup', this.unbindResizeMouseUp);

                this.$refs.popupBox.style.left = this.$refs.popupBox.offsetLeft + 'px';
                this.$refs.popupBox.style.margin = 0;
            },
            onResizeMouseMove(e) {
                this.widthPopup = e.clientX - this.offsetLeft - this.$refs.popupBox.offsetLeft;
                this.heightPopup = e.clientY - this.offsetTop - this.$refs.popupBox.offsetTop - this.$refs.popupHeader.offsetHeight;
            },
            unbindResizeMouseUp() {
                this.overfix = false;

                document.removeEventListener('mousemove', this.onResizeMouseMove);
                document.removeEventListener('mouseup', this.unbindResizeMouseUp);
            },

            onHeaderTouchStart(e) {
                if (e.target !== e.currentTarget) return;
                if (!e.touches || !e.touches[0]) return;

                e.preventDefault();

                let touch = e.touches[0];

                this.dndStartY = touch.clientY - this.offsetTop;

                document.addEventListener('touchmove', this.onDocTouchMove);
                document.addEventListener('touchend', this.unbindDocTouchEnd);

                this.unbindDocMouseUp();
            },
            onDocTouchMove(e) {
                if (!e.touches || !e.touches[0]) return;

                let touch = e.touches[0];
                this.offsetTop = touch.clientY - this.dndStartY;
            },
            unbindDocTouchEnd() {
                document.removeEventListener('touchmove', this.onDocTouchMove);
                document.removeEventListener('touchend', this.unbindDocTouchEnd);
            },
            close() {
                this.$emit('close');
            }
        },
        mounted() {
            document.body.classList.add('with-popup');
        },
        updated() {
            if (this.scrollToBottom == 1) {
                this.$nextTick(() => {
                    this.$refs.content.scrollTop = this.$refs.content.scrollHeight;
                });
            }
        },
        destroyed() {
            setTimeout(() => {
                document.body.classList.remove('with-popup');
            }, 200);
        }
    }
</script>

<style scoped>
    .fade-enter-active .popup-panel, .fade-leave-active .popup-panel {
        transition: transform .2s;
    }

    .fade-enter .popup-panel, .fade-leave-to .popup-panel {
        transform: translate(0, -25px);
    }

    .popup {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        padding: 5px 0 20px;
        box-sizing: border-box;
        background: rgba(43, 43, 43, 0.5);
        z-index: 999999;
        overflow: auto;
    }

    @media (min-height: 470px) {
        .popup {
            padding: 25px 0 20px;
        }

        .popup.modal-popup {
            padding: 35px 0 0;
        }
    }

    @media (min-height: 600px) {
        .popup {
            padding: 50px 0 20px;
        }

        .popup.modal-popup {
            padding: 60px 0 0;
        }
    }

    .popup-panel {
        position: relative;
        margin: 0 auto;
        width: 400px;
        max-width: 95%;
        font-size: 14px;
        background: #fff;
        border-radius: 3px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
    }

    .popup-header {
        position: relative;
        padding: 0 15px;
        height: 40px;
        line-height: 40px;
        font-weight: 500;
        font-size: 15px;
        color: white;
        background: #366356;
        border-radius: 3px 3px 0 0;
        cursor: grab;
    }

    .popup-header .left {
        cursor: text;
    }

    .popup-close {
        position: relative;
        cursor: pointer;
    }

    .popup-close:hover {
        opacity: 0.9;
    }

    .popup-content-outer {
        width: 100%;
        height: 100%;
    }

    .popup-footer {
        position: relative;
        padding: 0 15px 15px;
    }

    .popup-resizer-box {
        position: absolute;
        width: 14px;
        height: 14px;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at top left, transparent 68%, #366356 71%);
        cursor: nwse-resize;
    }

    .popup-resizer-box:hover {
        background: radial-gradient(circle at top left, transparent 65%, #366356 90%);
    }

    .overlaying-fix {
        position: fixed;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        cursor: nwse-resize;
        z-index: 1999999;
    }

    .popup-content {
        padding: 15px;
        max-height: 100%;
    }

    .popup-content.popup-scrollable {
        overflow-y: scroll;
    }

    .popup-tbl {
        width: 100%;
        border-collapse: collapse;
    }

    .popup-tbl th {
        font-weight: 500;
        text-transform: uppercase;
        background: #e9f0f8;
    }

    .popup-tbl th, td {
        padding: 3px;
        border: 1px solid #ccc;
        font-size: 13px;
    }
</style>