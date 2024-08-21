import $ from "jquery";

class Notes {
    constructor() {
        this.events();
        this.createNoteBtn = $('#create-note');
    }

    events() {
        $(document).on("click", ".delete-note", this.deleteNote.bind(this));
        $(".edit-note").on("click", this.editNote.bind(this));
        $(".update-note").on("click", this.updateNote.bind(this));
        $("#create-note").on("click", this.createNote.bind(this));
    }

    makeNoteEditable(nodeId) {
        nodeId
            .find(".note-title-field, .note-body-field")
            .removeAttr("readonly")
            .addClass("note-active-field");
        nodeId.find(".update-note").addClass("update-note--visible");

        nodeId
            .find(".edit-note")
            .html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');

        nodeId.data("state", "editable");
    }

    makeNoteReadonly(nodeId) {
        nodeId
            .find(".note-title-field, .note-body-field")
            .attr("readonly", "readonly")
            .removeClass("note-active-field");
        nodeId.find(".update-note").removeClass("update-note--visible");

        nodeId
            .find(".edit-note")
            .html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');

        nodeId.data("state", "readonly");
    }

    editNote(e) {
        var nodeId = $(e.target).parents("li");

        if (nodeId.data("state") == "editable") {
            this.makeNoteReadonly(nodeId);
        } else {
            this.makeNoteEditable(nodeId);
        }
    }

    deleteNote(e) {
        var nodeId = $(e.target).parents("li");

        var nodeId = $(e.target).closest("li");
        var postId = nodeId.data("id");

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", globalObj.nonce);
            },
            type: "DELETE",
            url: `${globalObj.rest_url}wp/v2/note/${postId}`,
        })
            .done((response) => {
                nodeId.slideUp(300);
                if ( response.user_posts_count < 5 ) {
                    $('.note-limit-message').removeClass('active')
                }
            })
            .error((response) => {
                console.log(response);
            });
    }

    updateNote(e) {
        var nodeId = $(e.target).parents("li");
        var updatePost = {
            title: nodeId.find(".note-title-field").val(),
            content: nodeId.find(".note-body-field").val(),
            acf: {
                main_body_content: nodeId.find(".note-body-field").val(),
            },
        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", globalObj.nonce);
            },
            type: "POST",
            data: updatePost,
            url: globalObj.rest_url + "wp/v2/note/" + nodeId.data("id"),
        })
            .done((response) => {
                console.log(response);
                this.makeNoteReadonly(nodeId);
            })
            .error((response) => {
                console.log(response);
            });
    }

    toggleCreateNoteBtn() {
        var $title = $('.new-note-title').val().trim();
        var $content = $('.new-note-body').val().trim();

        if ('' === $('.new-note-title').val() || '' === $('.new-note-body').val()) {
            this.createNoteBtn.addClass('disabled');
        } else {
            this.createNoteBtn.removeClass('disabled');
        }
    }

    createNote(e) {
        var $title = $('.new-note-title').val().trim();
        var $content = $('.new-note-body').val().trim();

        var createNoteData = {
            title: $title,
            content: $content,
            status: 'publish',
            acf: {
                main_body_content: $content,
            },
        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", globalObj.nonce);
            },
            type: "POST",
            data: createNoteData,
            url: globalObj.rest_url + "wp/v2/note/",
        })
            .done((response) => {
                var nodeId = response.id
                var title = response.title.rendered.replace('Private: ', '' );
                $('.new-note-title, .new-note-body').val('');
                $(`
                    <li class="post-item" data-id="${nodeId}">
					<input readonly type="text" class="note-title-field" value="${title}" />
					<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
					<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
					<textarea readonly class="note-body-field">${response.content.raw}</textarea>
					<span class="update-note btn btn--small btn--blue"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save Note</span>
				</li>
                    `).prependTo('.link-list').data('id', nodeId).hide().slideDown(300);
            })
            .error((response) => {
                console.log( response )
                if ( response.responseText === "You have reached your note limit" ) {
                    $('.note-limit-message').addClass('active')
                }
            });
    }
}

export default Notes;
