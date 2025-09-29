import './bootstrap';

import Alpine from 'alpinejs';

// BKC App タブナビゲーションのAlpine.jsコンポーネント
Alpine.data('bkcTabNavigation', () => ({
    activeTab: 'home',

    setActiveTab(tab) {
        this.activeTab = tab;
    },

    isActive(tab) {
        return this.activeTab === tab;
    }
}));

// ドライブカテゴリ切替のAlpine.jsコンポーネント
Alpine.data('driveCategory', () => ({
    activeCategory: 'shopping',

    setCategory(category) {
        this.activeCategory = category;
    },

    isActive(category) {
        return this.activeCategory === category;
    }
}));

// フリマタブ切替のAlpine.jsコンポーネント
Alpine.data('fleamarketTabs', () => ({
    activeTab: 'sell',

    setTab(tab) {
        this.activeTab = tab;
    },

    isActive(tab) {
        return this.activeTab === tab;
    }
}));

// マイページ作成カテゴリ切替のAlpine.jsコンポーネント
Alpine.data('myPageCreate', () => ({
    activeCategory: 'drive',

    setCategory(category) {
        this.activeCategory = category;
    },

    isActive(category) {
        return this.activeCategory === category;
    }
}));

// マイページ一覧カテゴリ切替のAlpine.jsコンポーネント
Alpine.data('myPageList', () => ({
    activeCategory: 'drive',

    setCategory(category) {
        this.activeCategory = category;
    },

    isActive(category) {
        return this.activeCategory === category;
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

// フィルターチップのAlpine.jsコンポーネント
Alpine.data('filterChips', () => ({
    filters: {
        cheap: false,
        near: false,
        nomihodai: false
    },

    toggleFilter(filter) {
        this.filters[filter] = !this.filters[filter];
    },

    isActive(filter) {
        return this.filters[filter];
    },

    clearFilters() {
        this.filters = {
            cheap: false,
            near: false,
            nomihodai: false
        };
    }
}));

// モーダルのAlpine.jsコンポーネント
Alpine.data('modal', () => ({
    isOpen: false,

    open() {
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = 'auto';
    }
}));

// 編集パネルのAlpine.jsコンポーネント
Alpine.data('editPanel', () => ({
    isEditing: false,

    startEdit() {
        this.isEditing = true;
    },

    cancelEdit() {
        this.isEditing = false;
    },

    saveEdit() {
        // ここで保存処理を実装
        this.isEditing = false;
    }
}));

// フォーム送信のAlpine.jsコンポーネント
Alpine.data('formSubmit', () => ({
    isSubmitting: false,

    async submit(formData) {
        this.isSubmitting = true;
        try {
            // フォーム送信処理を実装
            await new Promise(resolve => setTimeout(resolve, 1000)); // デモ用の遅延
            console.log('Form submitted:', formData);
        } catch (error) {
            console.error('Form submission error:', error);
        } finally {
            this.isSubmitting = false;
        }
    }
}));

window.Alpine = Alpine;

Alpine.start();
