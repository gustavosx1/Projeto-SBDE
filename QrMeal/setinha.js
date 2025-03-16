window.addEventListener('scroll', function() {
    const indicator = document.querySelector('.scroll-indicator');
    const scrollHeight = document.documentElement.scrollHeight;
    const scrollPosition = window.innerHeight + window.scrollY;

    if (scrollPosition >= scrollHeight-100) {
        indicator.classList.add('hidden');
    } else {
        indicator.classList.remove('hidden');
    }
});
