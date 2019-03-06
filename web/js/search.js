

$(function () {

    $(document.body).on('click', '#js-open-search', function () {

        $('#search').toggleClass('hidden');

        if ( !$('#search').hasClass('hidden') ) {
            $("html, body").animate({
                scrollTop: 0
            }, 1);
        }
    });

    $("#itemsearch-original_release_year").slider({
        value : $("#itemsearch-original_release_year").val()
    });
    $("#itemsearch-imdb_score").slider({
        value : $("#itemsearch-imdb_score").val()
    });
    $("#itemsearch-filmaffinity_score").slider({
        value : $("#itemsearch-filmaffinity_score").val()
    });

    $(document).on('pjax:success', function () {

        $("#itemsearch-original_release_year").slider({
            value : $("#itemsearch-original_release_year").val()
        });
        $("#itemsearch-imdb_score").slider({
            value : $("#itemsearch-imdb_score").val()
        });
        $("#itemsearch-filmaffinity_score").slider({
            value : $("#itemsearch-filmaffinity_score").val()
        });

        $("html, body").animate({
            scrollTop: 0
        }, 300);
    })

});


