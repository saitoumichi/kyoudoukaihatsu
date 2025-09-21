import './bootstrap';

import Alpine from 'alpinejs';

// タブナビゲーションのAlpine.jsコンポーネント
Alpine.data('tabNavigation', () => ({
    activeTab: 'home',

    setActiveTab(tab) {
        this.activeTab = tab;
    },

    isActive(tab) {
        return this.activeTab === tab;
    }
}));

// 検索フォームのAlpine.jsコンポーネント
Alpine.data('searchForm', () => ({
    searchQuery: '',
    isSearching: false,

    search() {
        if (this.searchQuery.trim()) {
            this.isSearching = true;
            window.location.href = `/places/search?q=${encodeURIComponent(this.searchQuery)}`;
        }
    },

    clearSearch() {
        this.searchQuery = '';
        this.isSearching = false;
    }
}));

// 場所カードのAlpine.jsコンポーネント
Alpine.data('placeCard', () => ({
    showDetails: false,

    toggleDetails() {
        this.showDetails = !this.showDetails;
    }
}));

window.Alpine = Alpine;

Alpine.start();
