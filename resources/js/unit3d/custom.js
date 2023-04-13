// Scroll To Top/Bottom
document.addEventListener('DOMContentLoaded', function() {
    let rootElement = document.documentElement;
    let topOfPage = document.getElementById('top-of-page');
    let bottomOfPage = document.getElementById('bottom-of-page');

    let handleScroll = function() {
        if (rootElement.scrollTop > 50) {
            topOfPage.style.opacity = 0.7
        } else {
            topOfPage.style.opacity = 0
        }

        if (rootElement.scrollTop < rootElement.scrollHeight - rootElement.clientHeight - 50) {
            bottomOfPage.style.opacity = 0.7
        } else {
            bottomOfPage.style.opacity = 0
        }
    }

    handleScroll();

    topOfPage.style.transition = 'opacity 0.8s';
    bottomOfPage.style.transition = 'opacity 0.8s';

    window.addEventListener('scroll', handleScroll);

    topOfPage.addEventListener('click', function (event) {
        event.preventDefault();
        rootElement.scrollTo({
            top: 0,
            behavior: "smooth",
        })
    });

    bottomOfPage.addEventListener('click', function (event) {
        event.preventDefault();
        rootElement.scrollTo({
            top: rootElement.scrollHeight,
            behavior: "smooth",
        })
    });
});
