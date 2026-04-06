// Header and Footer Manager
// This script loads header and footer from index.html and injects them into all pages

class HeaderFooterManager {
    constructor() {
        this.baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
        this.init();
    }

    async init() {
        try {
            // Load the index.html content
            const response = await fetch(this.baseUrl + 'index.html');
            const html = await response.text();
            
            // Parse the HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract header and footer
            const header = doc.querySelector('#header');
            const footer = doc.querySelector('#footer');
            
            if (header && footer) {
                // Inject header and footer into current page
                this.injectHeader(header);
                this.injectFooter(footer);
                
                // Update active navigation based on current page
                this.updateActiveNavigation();
                
                // Reinitialize any JavaScript that depends on header/footer
                this.reinitializeComponents();
            }
        } catch (error) {
            console.error('Error loading header and footer:', error);
        }
    }

    injectHeader(headerContent) {
        const existingHeader = document.querySelector('#header');
        if (existingHeader) {
            existingHeader.replaceWith(headerContent.cloneNode(true));
        }
    }

    injectFooter(footerContent) {
        const existingFooter = document.querySelector('#footer');
        if (existingFooter) {
            existingFooter.replaceWith(footerContent.cloneNode(true));
        }
    }

    updateActiveNavigation() {
        // Get current page filename
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        
        // Remove all active classes
        document.querySelectorAll('#navmenu a').forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to current page link
        document.querySelectorAll('#navmenu a').forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPage || (currentPage === 'index.html' && href === 'index.html')) {
                link.classList.add('active');
            }
        });
    }

    reinitializeComponents() {
        // Reinitialize mobile navigation toggle
        const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        if (mobileNavToggle) {
            mobileNavToggle.addEventListener('click', () => {
                document.body.classList.toggle('mobile-nav-active');
            });
        }

        // Reinitialize scroll to top
        const scrollTop = document.getElementById('scroll-top');
        if (scrollTop) {
            scrollTop.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Trigger any custom events for other scripts
        document.dispatchEvent(new CustomEvent('headerFooterLoaded'));
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new HeaderFooterManager();
});
