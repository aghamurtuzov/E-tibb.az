<template>
    <div v-if="isVisible">
        <div class="overlay__custom">
        </div>
        <div class="cssload-box-loading">
        </div>
    </div>
    <div v-else="">
    </div>
</template>

<script>
    export default {
        name: "Loading",
        data: function(){
             return {
                 loading: true
             }
        },
        watch:{
            isVisible: function(newVal, oldVal) {
                this.loading = newVal;
            }
        },
        props: {
            isVisible: {type: Boolean, required: true},
            text: {type: String, required: false, default: ""},
        },
        mounted() {
            this.loading = this.$props.isVisible;
        }
    }
</script>

<style scoped>
    .overlay__custom {
        background: #c4f9ed;
        position: fixed;
        top: 0;
        -webkit-backdrop-filter: blur(10px);
        right: 0;
        bottom: 0;
        left: 0;
        opacity: .7;
        z-index: 999;
        width: 100%;
    }

    .cssload-box-loading {
        width: 100px;
        height: 100px;
        margin: auto;
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: 999999999;
    }

    .cssload-box-loading:before {
        content: '';
        width: 100px;
        height: 10px;
        background: rgb(20,187,176);
        opacity: 0.1;
        position: absolute;
        top: 125px;
        left: 0;
        border-radius: 50%;
        animation: shadow 0.58s linear infinite;
        -o-animation: shadow 0.58s linear infinite;
        -ms-animation: shadow 0.58s linear infinite;
        -webkit-animation: shadow 0.58s linear infinite;
        -moz-animation: shadow 0.58s linear infinite;
    }

    .cssload-box-loading:after {
        content: '';
        width: 100px;
        height: 100px;
        background: rgb(20,187,176);
        position: absolute;
        top: 0;
        left: 0;
        border-radius: 9px;
        animation: cssload-animate 0.58s linear infinite;
        -o-animation: cssload-animate 0.58s linear infinite;
        -ms-animation: cssload-animate 0.58s linear infinite;
        -webkit-animation: cssload-animate 0.58s linear infinite;
        -moz-animation: cssload-animate 0.58s linear infinite;
    }

    @keyframes cssload-animate {
        17% {
            border-bottom-right-radius: 9px;
        }
        25% {
            transform: translateY(26px) rotate(22.5deg);
        }
        50% {
            transform: translateY(52px) scale(1, 0.9) rotate(45deg);
            border-bottom-right-radius: 115px;
        }
        75% {
            transform: translateY(26px) rotate(67.5deg);
        }
        100% {
            transform: translateY(0) rotate(90deg);
        }
    }

    @-o-keyframes cssload-animate {
        17% {
            border-bottom-right-radius: 9px;
        }
        25% {
            -o-transform: translateY(26px) rotate(22.5deg);
        }
        50% {
            -o-transform: translateY(52px) scale(1, 0.9) rotate(45deg);
            border-bottom-right-radius: 115px;
        }
        75% {
            -o-transform: translateY(26px) rotate(67.5deg);
        }
        100% {
            -o-transform: translateY(0) rotate(90deg);
        }
    }

    @-ms-keyframes cssload-animate {
        17% {
            border-bottom-right-radius: 9px;
        }
        25% {
            -ms-transform: translateY(26px) rotate(22.5deg);
        }
        50% {
            -ms-transform: translateY(52px) scale(1, 0.9) rotate(45deg);
            border-bottom-right-radius: 115px;
        }
        75% {
            -ms-transform: translateY(26px) rotate(67.5deg);
        }
        100% {
            -ms-transform: translateY(0) rotate(90deg);
        }
    }

    @-webkit-keyframes cssload-animate {
        17% {
            border-bottom-right-radius: 9px;
        }
        25% {
            -webkit-transform: translateY(26px) rotate(22.5deg);
        }
        50% {
            -webkit-transform: translateY(52px) scale(1, 0.9) rotate(45deg);
            border-bottom-right-radius: 115px;
        }
        75% {
            -webkit-transform: translateY(26px) rotate(67.5deg);
        }
        100% {
            -webkit-transform: translateY(0) rotate(90deg);
        }
    }

    @-moz-keyframes cssload-animate {
        17% {
            border-bottom-right-radius: 9px;
        }
        25% {
            -moz-transform: translateY(26px) rotate(22.5deg);
        }
        50% {
            -moz-transform: translateY(52px) scale(1, 0.9) rotate(45deg);
            border-bottom-right-radius: 115px;
        }
        75% {
            -moz-transform: translateY(26px) rotate(67.5deg);
        }
        100% {
            -moz-transform: translateY(0) rotate(90deg);
        }
    }

    @keyframes shadow {
        0%,
        100% {
            transform: scale(1, 1);
        }
        50% {
            transform: scale(1.2, 1);
        }
    }

    @-o-keyframes shadow {
        0%,
        100% {
            -o-transform: scale(1, 1);
        }
        50% {
            -o-transform: scale(1.2, 1);
        }
    }

    @-ms-keyframes shadow {
        0%,
        100% {
            -ms-transform: scale(1, 1);
        }
        50% {
            -ms-transform: scale(1.2, 1);
        }
    }

    @-webkit-keyframes shadow {
        0%,
        100% {
            -webkit-transform: scale(1, 1);
        }
        50% {
            -webkit-transform: scale(1.2, 1);
        }
    }

    @-moz-keyframes shadow {
        0%,
        100% {
            -moz-transform: scale(1, 1);
        }
        50% {
            -moz-transform: scale(1.2, 1);
        }
    }


</style>
