import $ from "jquery";

class Search {
    constructor() {
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.overlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.resultDiv = $(".search-overlay__results .container");
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue = "";
        this.events();
    }

    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogin.bind(this));
    }

    typingLogin() {
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);

            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 1300);
            } else {
                this.resultDiv.html("");
                this.isSpinnerVisible = false;
            }
        }

        this.previousValue = this.searchField.val();
    }

    getResults() {
        $.when(
            $.getJSON(globalObj.rest_url + 'wp/v2/posts?search=' + this.searchField.val()),
            $.getJSON(globalObj.rest_url + 'wp/v2/pages?search=' + this.searchField.val())
        ).done((postsResponse, pagesResponse) => {
            const posts = postsResponse[0];
            const pages = pagesResponse[0];
            const postsAndPages = posts.concat(pages);
        
            console.log('postsAndPages: ', postsAndPages);
        
            this.resultDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${postsAndPages.length ? '<ul class="link-list min-list">' : `<p>No results for ${this.searchField.val()}</p>`}
                    ${postsAndPages.map(item => `<li><a href="${item.link}">${item.title.rendered}</a>${item.author_name ? ` by ${item.author_name}` : ""}</li>`).join('')}
                ${postsAndPages.length ? '</ul>' : ''}
            `);
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.error('An error occurred:', textStatus, errorThrown);
            this.resultDiv.html('<p>There was an error processing your request. Please try again later.</p>');
        }).always(() => {
            this.isSpinnerVisible = false;
        });        
    }

    keyPressDispatcher(e) {
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(":focus")) {
            this.openOverlay();
        }

        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.overlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val("");
        setTimeout(() => this.searchField.focus(), 301);
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.overlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }
}

export default Search;
