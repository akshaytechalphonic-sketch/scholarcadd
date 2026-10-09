<div class="loader-wrapper">
    <div class="loadermain">
        <div class="dots"></div>
        <div class="dots"></div>
        <div class="dots"></div>
        <div class="dots"></div>
        <div class="dots"></div>
        <div class="dots"></div>
        <div class="dots"></div>
        <div class="dots"></div>
    </div>
</div>
<style>
    .loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 1);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 98%;
    }

    .loadermain {
        --uib-size: 2.8rem;
        --uib-speed: .9s;
        --uib-color: #1F3F49;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: var(--uib-size);
        width: var(--uib-size);
    }

    .dots {
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: 100%;
        width: 100%;
    }

    .dots::before {
        content: '';
        height: 20%;
        width: 20%;
        border-radius: 50%;
        background-color: var(--uib-color);
        transform: scale(0);
        opacity: 0.5;
        animation: pulse0112 calc(var(--uib-speed) * 1.111) ease-in-out infinite;
        box-shadow: 0 0 20px rgba(18, 31, 53, 0.3);
    }

    .dots:nth-child(2) {
        transform: rotate(45deg);
    }

    .dots:nth-child(2)::before {
        animation-delay: calc(var(--uib-speed) * -0.875);
    }

    .dots:nth-child(3) {
        transform: rotate(90deg);
    }

    .dots:nth-child(3)::before {
        animation-delay: calc(var(--uib-speed) * -0.75);
    }

    .dots:nth-child(4) {
        transform: rotate(135deg);
    }

    .dots:nth-child(4)::before {
        animation-delay: calc(var(--uib-speed) * -0.625);
    }

    .dots:nth-child(5) {
        transform: rotate(180deg);
    }

    .dots:nth-child(5)::before {
        animation-delay: calc(var(--uib-speed) * -0.5);
    }

    .dots:nth-child(6) {
        transform: rotate(225deg);
    }

    .dots:nth-child(6)::before {
        animation-delay: calc(var(--uib-speed) * -0.375);
    }

    .dots:nth-child(7) {
        transform: rotate(270deg);
    }

    .dots:nth-child(7)::before {
        animation-delay: calc(var(--uib-speed) * -0.25);
    }

    .dots:nth-child(8) {
        transform: rotate(315deg);
    }

    .dots:nth-child(8)::before {
        animation-delay: calc(var(--uib-speed) * -0.125);
    }

    @keyframes pulse0112 {

        0%,
        100% {
            transform: scale(0);
            opacity: 0.5;
        }

        50% {
            transform: scale(1);
            opacity: 1;
        }
    }

    body.loaded .loader-wrapper {
        opacity: 0;
        pointer-events: none;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add("loaded");
    });
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            document.body.classList.remove(
                "loaded");
            setTimeout(() => document.body.classList.add("loaded"), 0);
        }
    });
    window.addEventListener("beforeunload", function() {
        document.body.classList.remove("loaded");
    });
</script>
