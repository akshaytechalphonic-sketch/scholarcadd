
<script>
    function toggleAccordion(index) {
        const allContents = document.querySelectorAll('[id^="content-"]');
        const currentContent = document.getElementById(`content-${index}`);
        const currentIcon = document.querySelector(`#icon-${index} i`);
        allContents.forEach((content, i) => {
            if (content !== currentContent) {
                content.style.maxHeight = '0';
                const icon = document.querySelector(`#icon-${i} i`);
                if (icon) {
                    icon.classList.remove('ri-subtract-line');
                    icon.classList.add('ri-add-line');
                }
            }
        });
        if (currentContent.style.maxHeight && currentContent.style.maxHeight !== '0px') {
            currentContent.style.maxHeight = '0';
            if (currentIcon) {
                currentIcon.classList.remove('ri-subtract-line');
                currentIcon.classList.add('ri-add-line');
            }
        } else {
            currentContent.style.maxHeight = currentContent.scrollHeight + 'px';
            if (currentIcon) {
                currentIcon.classList.remove('ri-add-line');
                currentIcon.classList.add('ri-subtract-line');
            }
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
    const defaultIndex = 5;
    const defaultContent = document.getElementById(`content-${defaultIndex}`);
    const defaultIcon = document.querySelector(`#icon-${defaultIndex} i`);

    if (defaultContent) {
        defaultContent.style.maxHeight = defaultContent.scrollHeight + 'px';
    }
    if (defaultIcon) {
        defaultIcon.classList.remove('ri-add-line');
        defaultIcon.classList.add('ri-subtract-line');
    }
});

</script>
