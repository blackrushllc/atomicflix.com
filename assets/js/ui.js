export function initMobileMenu() {
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('nav');
    
    if (navToggle && nav) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            nav.classList.toggle('active');
            
            // Prevent scrolling when menu is open
            if (nav.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        });

        // Close menu when clicking a link
        const navLinks = nav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                nav.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });
    }
}

export function initScrollButtons() {
    const wrappers = document.querySelectorAll('.row-wrapper');

    wrappers.forEach(wrapper => {
        const container = wrapper.querySelector('.cards-container');
        const nextBtn = wrapper.querySelector('.scroll-btn.next');
        const prevBtn = wrapper.querySelector('.scroll-btn.prev');

        if (!container || !nextBtn || !prevBtn) return;

        nextBtn.addEventListener('click', () => {
            // Scroll by 80% of the visible container width
            const scrollAmount = container.clientWidth * 0.8;
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        prevBtn.addEventListener('click', () => {
            const scrollAmount = container.clientWidth * 0.8;
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        // Optional: Hide/Show buttons based on scroll position
        const updateButtons = () => {
            const scrollLeft = container.scrollLeft;
            const maxScroll = container.scrollWidth - container.clientWidth;

            // Hide/Show prev button if at the start
            if (scrollLeft <= 5) {
                prevBtn.style.visibility = 'hidden';
            } else {
                prevBtn.style.visibility = 'visible';
            }

            // Hide/Show next button if at the end
            if (scrollLeft >= maxScroll - 5) {
                nextBtn.style.visibility = 'hidden';
            } else {
                nextBtn.style.visibility = 'visible';
            }
        };

        // Update on scroll
        container.addEventListener('scroll', updateButtons);
        // Initial check
        setTimeout(updateButtons, 100);
        // Update on window resize
        window.addEventListener('resize', updateButtons);
    });
}
