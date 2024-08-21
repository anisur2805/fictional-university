import $ from "jquery";

class Like {
    constructor() {
        this.events();
    }

    events() {
        $(document).on("click", ".like-box", this.dispatchLike.bind(this));
    }

    dispatchLike(e) {
        e.preventDefault();

        let currentLikeBox = $(e.target).closest(".like-box");
        if (currentLikeBox.data("exists") == "yes") {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentTarget) {
        let professorId = currentTarget.data("professor");
        let professorTitle = currentTarget.data("title");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", globalObj.nonce);
            },
            type: "POST",
            url: `${globalObj.rest_url}university/v1/likes`,
            data: { professor: professorId, title: professorTitle },
        })
            .done((response) => {
                if (response.success) {
                    currentTarget.attr("data-exists", "yes");
                    currentTarget.attr("data-like", response.data.post_id);
                    let likeCount = parseInt(currentTarget.find(".like-count").text(), 10);
                    currentTarget.find(".like-count").text(likeCount + 1);
                }

                if ( !response.success) {
                    alert( response.data.message );
                }
            })
            .fail((response) => {
                if ( !! response.success ) {
                    alert( response.data.message );
                }
            });
    }

    deleteLike(currentTarget) {
        let professorId = currentTarget.data("professor");
        let like_id = currentTarget.data("like");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", globalObj.nonce);
            },
            type: "DELETE",
            url: `${globalObj.rest_url}university/v1/likes/${professorId}`,
            data: { like_id: like_id },
        })
            .done((response) => {
                if (response.success) {
                    currentTarget.attr("data-exists", "no");
                    let likeCount = parseInt(currentTarget.find(".like-count").text(), 10);
                    currentTarget.find(".like-count").text(likeCount - 1);
                }
            })
            .fail((response) => {
                console.log(response);
            });
    }
}

export default Like;
