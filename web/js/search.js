var padding_item_list;

$(function () {

    $(document.body).on('click', '#js-open-search', function () {

        $('#search').toggleClass('hidden');

        // move to top
        $("html, body").animate({
            scrollTop: 0
        }, 1);

        if ( !$('#search').hasClass('hidden') ) {
            padding_item_list = $('#js-item-index').css('padding-top');
            // remove padding on list
            $('#js-item-index').css('padding-top',0);
        } else {
            $('#js-item-index').css('padding-top',padding_item_list);
        }
    });

    if ( $("#itemsearch-original_release_year").length > 0  ) {

        var range = $("#itemsearch-original_release_year").val().split(",");

        $("#itemsearch-original_release_year").slider({
            tooltip_position:'top',
            value: [ (range[0] ? parseInt(range[0]) : 1940), range[1] ? parseInt(range[1]) : parseInt($("#itemsearch-original_release_year").attr('data-slider-max'))]
        });
        $("#itemsearch-imdb_score").slider({
            value : $("#itemsearch-imdb_score").val(),
            tooltip_position:'top'
        });
        $("#itemsearch-filmaffinity_score").slider({
            value : $("#itemsearch-filmaffinity_score").val(),
            tooltip_position:'top'
        });
    }



    $(document).on('pjax:success', function () {

        if ( $("#itemsearch-original_release_year").length > 0 ) {

            var range = $("#itemsearch-original_release_year").val().split(",");
            $("#itemsearch-original_release_year").slider({
                tooltip_position: 'top',
                value: [(range[0] ? parseInt(range[0]) : 1940), range[1] ? parseInt(range[1]) : parseInt($("#itemsearch-original_release_year").attr('data-slider-max'))]
            });
            $("#itemsearch-imdb_score").slider({
                value: $("#itemsearch-imdb_score").val(),
                tooltip_position: 'top'
            });
            $("#itemsearch-filmaffinity_score").slider({
                value: $("#itemsearch-filmaffinity_score").val(),
                tooltip_position: 'top'
            });

            $("html, body").animate({
                scrollTop: 0
            }, 300);

        }
    })

});


