import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('liveSearch', () => ({
    init() {
        this.$el.addEventListener('input', (e) => {
            if (e.target && e.target.name === 'search') {
                this.debouncedSearch(e.target.form);
            }
        });
        
        this.$el.addEventListener('change', (e) => {
            if (e.target && e.target.tagName === 'SELECT' && e.target.form && e.target.form.querySelector('input[name="search"]')) {
                this.performSearch(e.target.form);
            }
        });
    },
    
    debouncedSearch: Alpine.debounce(function(form) {
        this.performSearch(form);
    }, 500),
    
    async performSearch(form) {
        if (!form) return;
        const url = new URL(form.action || window.location.href);
        const formData = new FormData(form);
        
        for (const [key, value] of formData.entries()) {
            if (value) url.searchParams.set(key, value);
            else url.searchParams.delete(key);
        }
        
        window.history.pushState({}, '', url);
        
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const currentResults = document.getElementById('table-container');
            const newResults = doc.getElementById('table-container');
            
            if (currentResults && newResults) {
                currentResults.innerHTML = newResults.innerHTML;
            }
        } catch(e) {
            console.error('Live search error:', e);
        }
    }
}));

Alpine.start();
