function scrollToSection(id) {
    const section = document.getElementById(id);
    const menuHeight = document.querySelector('.menu').offsetHeight;
    window.scrollTo({
        top: section.offsetTop - menuHeight,
        behavior: 'smooth'
    });
}

function onScroll() {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= window.innerHeight && rect.bottom >= 0) {
            section.classList.add('visible');
        } else {
            section.classList.remove('visible');
        }
    });
}

function handleScroll() {
    if (!scrolling) {
        requestAnimationFrame(onScroll);
        scrolling = true;
    }
    scrolling = false;
}

let scrolling = false;

window.addEventListener('scroll', handleScroll);
document.addEventListener('DOMContentLoaded', onScroll);
