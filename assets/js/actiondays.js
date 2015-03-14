/**
 * Created by Valery on 18.01.2015.
 */
$(function() {
    var $filters = $(".slider-buttons #filters");
    $filters.on("click", "li", function(){
        var $filter = $(this).find("span");
        if (!$filter.hasClass('active')) {
            $filters.find("span.active").removeClass('active');
            $filter.addClass(" active");
            $("#sliderlist div.active").removeClass('active').fadeOut('slow', 'linear', function(){
                ($("." + $filter.data('filter'))).fadeIn('slow', 'linear').addClass('active')
            });
        }
    });
});

$(document).ready(function () {
    $(".dropdown").on("mouseout", function() {
        $(this).removeClass('open');
    }).on("mouseover", function() {
        $(this).addClass('open');
    });

    $("[data-role=languages]").on("click", "a", function(event) {
        event.preventDefault();
        var lang = $(this).data("url");
        var cleanUrl = window.location.href.replace(/[&\?]lang=[^&]*/i, '');
        var langParam = cleanUrl.indexOf('?') != -1 ? "&lang=" + lang : "?lang=" + lang;
        window.location = cleanUrl + langParam;
    });

    $(".match_score").each(function() {
        if ($(this).text() == 2) {
            var dataScore = $(this).data("score");
            $(this).addClass("winner");
            $(this).closest("tr").find("[data-player=" + dataScore + "]").addClass("winner");
        }
    });

    var $galleryContainer = $("#galleryContainer");
    $galleryContainer.justifiedGallery({
        rowHeight: 200,
        fixedHeight: false,
        lastRow: 'nojustify',
        margins: 10,
        randomize: false,
        sizeRangeSuffixes: {
            'lt100':'',
            'lt240':'',
            'lt320':'',
            'lt500':'',
            'lt640':'',
            'lt1024':''
        }
    }).on('jg.complete', function () {
        $galleryContainer.find('a').swipebox({
            //useCSS : false // false will force the use of jQuery for animations
        });
    });

    $(".moreDialog").on("click", function(event) {
        event.preventDefault();

        var playerId = $(this).data('profile');
        $.ajax({
            url: "?section=profile&profile=" + playerId,
            type: "GET",
            success: function(response) {
                var title = $(response).find("h5.playerFullName").text();


                $(response).dialog({
                    create:function () {
                        $(this).closest(".ui-dialog")
                            .find(".ui-dialog-titlebar-close") // the first button
                            .hide();
                        $(this).closest(".ui-dialog")
                            .find(".ui-dialog-buttonset button") // the first button
                            .addClass("btn btn-blue");
                    },
                    dialogClass: "no-close",
                    show: 'fadeIn',
                    hide: 'fadeOut',
                    modal: true,
                    draggable: false,
                    resizable: false,
                    closeOnEscape: true,
                    autoOpen: true,
                    width: '500px',
                    title: title,
                    buttons: [
                        {
                            text: "OK",
                            click: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    ]
                })
            }
        })
    });

    $('.more').on('click', function(event) {
        event.preventDefault();

        var href = $(this).find('a').attr('href'),
            portfolioList = $('#sliderlist'),
            content = $('#loaded-content');

        portfolioList.animate({'marginLeft':'-120%'},{duration:400,queue:false});
        portfolioList.fadeOut(400);
        setTimeout(function(){
            console.log($('#loader'));
            $('#loader').show();
            console.log($('#loader'));
        },400);
        setTimeout(function(){
            content.load(href, function() {
                $('#loaded-content meta').remove();
                $('#loader').hide();
                content.fadeIn(600);
                $('#back-button').fadeIn(600);
            });
        },800);

    });

    $('#back-button').on('click', function(event) {
        event.preventDefault();

        var portfolioList = $('#sliderlist')
        content = $('#loaded-content');

        content.fadeOut(400);
        $('#back-button').fadeOut(400);
        setTimeout(function(){
            portfolioList.animate({'marginLeft':'0'},{duration:400,queue:false});
            portfolioList.fadeIn(600);
        },800);
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });

    $(function() {
        $("body").on("input propertychange", ".floating-label-form-group", function(e) {
            $(this).toggleClass("floating-label-form-group-with-value", !!$(e.target).val());
        }).on("focus", ".floating-label-form-group", function() {
            $(this).addClass("floating-label-form-group-with-focus");
        }).on("blur", ".floating-label-form-group", function() {
            $(this).removeClass("floating-label-form-group-with-focus");
        });
    });
});
