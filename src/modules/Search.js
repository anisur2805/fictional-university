import axios from "axios";

class Search {
    constructor() {
        this.openButton = document.querySelectorAll(".js-search-trigger");
        this.closeButton = document.querySelector(".search-overlay__close");
        this.overlay = document.querySelector(".search-overlay");
        this.searchField = document.querySelector("#search-term");
        this.resultDiv = document.querySelector(
            ".search-overlay__results .container"
        );
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue = "";
        this.events();
    }

    events() {
        this.openButton.forEach((el) => {
            el.addEventListener("click", (e) => {
                console.log( el )
                e.preventDefault();
                this.openOverlay();
            });
        });

        this.closeButton.addEventListener("click", this.closeOverlay.bind(this));
        document.addEventListener("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.addEventListener("keyup", this.typingLogin.bind(this));
    }

    typingLogin() {
        if (this.searchField.value != this.previousValue) {
            clearTimeout(this.typingTimer);

            if (this.searchField.value) {
                if (!this.isSpinnerVisible) {
                    this.resultDiv.innerHTML = '<div class="spinner-loader"></div>'
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 1300);
            } else {
                this.resultDiv.innerHTML = "";
                this.isSpinnerVisible = false;
            }
        }

        this.previousValue = this.searchField.value;
    }

    async getResults() {
        try {
            const response = await axios.get(
                globalObj.rest_url +
                    "university/v1/search?term=" +
                    this.searchField.value
            );
            const results = response.data;
            this.resultDiv.innerHTML = `
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${
                            results.generalInfo.length
                                ? '<ul class="link-list min-list">'
                                : `<p>No results for ${this.searchField.value}</p>`
                        }
                            ${results.generalInfo
                                .map(
                                    (item) =>
                                        `<li><a href="${item.link}">${
                                            item.title
                                        }</a>${
                                            item.post_type === "post"
                                                ? ` by ${item.author}`
                                                : ""
                                        }</li>`
                                )
                                .join("")}
                        ${results.generalInfo.length ? "</ul>" : ""}
                    </div>

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${
                            results.professors.length
                                ? '<ul class="professor-cards link-list min-list">'
                                : `<p>No results for ${this.searchField.value} <a href="${
                                      globalObj.root_url
                                  }/professors">View All Professors</a></p>`
                        }
                        ${results.professors
                            .map(
                                (item) =>
                                    `<li class="professor-card__list-item"><a class="professor-card" href="${item.link}"><img src="${item.image}" class="professor-card__image" alt="${item.title}"><span class="professor-card__name">${item.title}</span></a></li>`
                            )
                            .join("")}
                        ${results.professors.length ? "</ul>" : ""}
                    
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${
                            results.events.length
                                ? '<ul class="event-summary min-list">'
                                : `<p>No results for ${this.searchField.value} <a href="${
                                      globalObj.root_url
                                  }/events">View All Events</a></p>`
                        }
                        ${results.events
                            .map(
                                (
                                    item
                                ) => `<li><a class="event-summary__date t-center" href="${item.link}"><span class="event-summary__month">${item.month}</span><span class="event-summary__day">${item.day}</span></a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${item.link}">${item.title}</a></h5>
                                <p>${item.excerpt} <a href="${item.link}" class="nu gray">Learn more</a></p></div></li>`
                            )
                            .join("")}
                        ${results.events.length ? "</ul>" : ""}
                    </div>
                    
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${
                            results.campuses.length
                                ? '<ul class="link-list min-list">'
                                : `<p>No results for ${this.searchField.value} <a href="${
                                      globalObj.root_url
                                  }/campus">View All Campuses</a></p>`
                        }
                        ${results.campuses
                            .map(
                                (item) =>
                                    `<li><a href="${item.link}">${item.title}</a></li>`
                            )
                            .join("")}
                        ${results.campuses.length ? "</ul>" : ""}
                    
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${
                            results.programs.length
                                ? '<ul class="link-list min-list">'
                                : `<p>No results for ${this.searchField.value} <a href="${
                                      globalObj.root_url
                                  }/campus">View All Campuses</a></p>`
                        }
                        ${results.programs
                            .map(
                                (item) =>
                                    `<li><a href="${item.link}">${item.title}</a></li>`
                            )
                            .join("")}
                        ${results.programs.length ? "</ul>" : ""}

                    </div>
                </div>
            `;
            this.isSpinnerVisible = false;
        } catch (error) {
            console.error("An error occurred:", error);
            this.resultDiv.innerHTML = "<p>There was an error processing your request. Please try again later.</p>";
        }
    }

    keyPressDispatcher(e) {
        if (
            e.keyCode == 83 &&
            !this.isOverlayOpen &&
            ! document.activeElement.matches("input, textarea")
        ) {
            this.openOverlay();
        }

        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.overlay.classList.add("search-overlay--active");
        console.log( 'here' )
        document.querySelector("body").classList.add("body-no-scroll");
        this.searchField.value = "";
        setTimeout(() => this.searchField.focus(), 301);
        this.isOverlayOpen = true;
        return false;
    }

    closeOverlay() {
        this.overlay.classList.remove("search-overlay--active");
        document.querySelector("body").classList.remove("body-no-scroll");
        this.isOverlayOpen = false;
    }
}

export default Search;
