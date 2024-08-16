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
        $.getJSON(globalObj.rest_url + 'university/v1/search?term=' + this.searchField.val())
            .done((results) => {
                this.resultDiv.html(`
                    <div class="row">
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">General Information</h2>
                            ${results.generalInfo.length ? '<ul class="link-list min-list">' : `<p>No results for ${this.searchField.val()}</p>`}
                                ${results.generalInfo.map(item => `<li><a href="${item.link}">${item.title}</a>${item.post_type === "post" ? ` by ${item.author}` : ""}</li>`).join('')}
                            ${results.generalInfo.length ? '</ul>' : ''}
                        </div>

                        <div class="one-third">
                            <h2 class="search-overlay__section-title">Professors</h2>
                            ${results.professors.length ? '<ul class="professor-cards link-list min-list">' : `<p>No results for ${this.searchField.val()} <a href="${globalObj.root_url}/professors">View All Professors</a></p>`}
                            ${results.professors.map(item => `<li class="professor-card__list-item"><a class="professor-card" href="${item.link}"><img src="${item.image}" class="professor-card__image" alt="${item.title}"><span class="professor-card__name">${item.title}</span></a></li>`).join('')}
                            ${results.professors.length ? '</ul>' : ''}
                        
                            <h2 class="search-overlay__section-title">Events</h2>
                            ${results.events.length ? '<ul class="event-summary min-list">' : `<p>No results for ${this.searchField.val()} <a href="${globalObj.root_url}/events">View All Events</a></p>`}
                            ${results.events.map(item => `<li><a class="event-summary__date t-center" href="${item.link}"><span class="event-summary__month">${item.month}</span><span class="event-summary__day">${item.day}</span></a>
                                <div class="event-summary__content">
                                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.link}">${item.title}</a></h5>
                                    <p>${item.excerpt} <a href="${item.link}" class="nu gray">Learn more</a></p></div></li>`).join('')}
                            ${results.events.length ? '</ul>' : ''}
                        </div>
                        
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">Campuses</h2>
                            ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No results for ${this.searchField.val()} <a href="${globalObj.root_url}/campus">View All Campuses</a></p>`}
                            ${results.campuses.map(item => `<li><a href="${item.link}">${item.title}</a></li>`).join('')}
                            ${results.campuses.length ? '</ul>' : ''}
                        
                            <h2 class="search-overlay__section-title">Programs</h2>
                            ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No results for ${this.searchField.val()} <a href="${globalObj.root_url}/campus">View All Campuses</a></p>`}
                            ${results.programs.map(item => `<li><a href="${item.link}">${item.title}</a></li>`).join('')}
                            ${results.programs.length ? '</ul>' : ''}

                        </div>
                    </div>
                `);
                this.isSpinnerVisible = false;
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                console.error('An error occurred:', textStatus, errorThrown);
                this.resultDiv.html('<p>There was an error processing your request. Please try again later.</p>');
            })
            .always(() => {
                this.isSpinnerVisible = false;
            });

        // $.when(
        //     $.getJSON(globalObj.rest_url + 'wp/v2/posts?search=' + this.searchField.val()),
        //     $.getJSON(globalObj.rest_url + 'wp/v2/pages?search=' + this.searchField.val())
        // ).done((postsResponse, pagesResponse) => {
        //     const posts = postsResponse[0];
        //     const pages = pagesResponse[0];
        //     const postsAndPages = posts.concat(pages);
        
        //     console.log('postsAndPages: ', postsAndPages);
        
        //     this.resultDiv.html(`
        //         <h2 class="search-overlay__section-title">General Information</h2>
        //         ${postsAndPages.length ? '<ul class="link-list min-list">' : `<p>No results for ${this.searchField.val()}</p>`}
        //             ${postsAndPages.map(item => `<li><a href="${item.link}">${item.title}</a>${item.author ? ` by ${item.author}` : ""}</li>`).join('')}
        //         ${postsAndPages.length ? '</ul>' : ''}
        //     `);
        // }).fail((jqXHR, textStatus, errorThrown) => {
        //     console.error('An error occurred:', textStatus, errorThrown);
        //     this.resultDiv.html('<p>There was an error processing your request. Please try again later.</p>');
        // }).always(() => {
        //     this.isSpinnerVisible = false;
        // });        
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
