import $ from 'jquery';

class Search {
    constructor() {
        this.openButton = $('.js-search-trigger');
        this.closeButton = $('.search-overlay__close');
        this.overlay = $('.search-overlay');
        this.isOverlayOpen = false;

        this.events();
    }

    events() {
        this.openButton.on('click', this.openOverlay.bind(this));
        this.closeButton.on('click', this.closeOverlay.bind(this));
        $(document).on('keydown', this.keyPressDispatcher.bind(this));
    }

    keyPressDispatcher(e) {
        if ( e.keyCode == 83 && !this.isOverlayOpen ) {
            this.openOverlay()
        }

        if ( e.keyCode == 27 && this.isOverlayOpen ) {
            this.closeOverlay()
        }
    }

    openOverlay() {
        this.overlay.addClass('search-overlay--active');
        $('body').addClass('body-no-scroll');
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.overlay.removeClass('search-overlay--active');
        $('body').removeClass('body-no-scroll');
        this.isOverlayOpen = false
    }
}

export default Search;