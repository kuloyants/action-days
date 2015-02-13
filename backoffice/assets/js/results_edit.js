$(document).ready(function () {

    $("[data-role='edit']").on("click", function(event) {
        event.preventDefault(); // prevent default submit behaviour
        var data = {};
        $match = $(this.closest("tr"));
        data.match_nr           = $(this).data("match");
        data.player1_id         = $match.find("select[name='player1']").val();
        data.player2_id         = $match.find("select[name='player2']").val();
        data.score_p1_match     = $match.find("select[name='score_p1_match']").val();
        data.score_p2_match     = $match.find("select[name='score_p2_match']").val();
        data.score_p1_set1      = $match.find("select[name='score_p1_set1']").val();
        data.score_p1_set2      = $match.find("select[name='score_p1_set2']").val();
        data.score_p1_set3      = $match.find("select[name='score_p1_set3']").val();
        data.score_p2_set1      = $match.find("select[name='score_p2_set1']").val();
        data.score_p2_set2      = $match.find("select[name='score_p2_set2']").val();
        data.score_p2_set3      = $match.find("select[name='score_p2_set3']").val();
        data.status             = $match.find("select[name='status']").val();

        $match.find("[data-role=score]").each(function (){
            if ($(this).find("select").val() == 2) {
                data.winner = $(this).data("player");
                data.winnerToMatch = $match.data("winnertomatch");
                data.winnerToSlot = $match.data("winnertoslot");
            }
        });

        $.ajax({
            url: "?section=results",
            type: "POST",
            data: data,
            success: function(response) {
                console.log('BIN DURCH')
                //$("#registration").replaceWith($(response));
            },
            error: function() {
                $( '<div/>' ).dialog({
                    dialogClass: "no-close",
                    autoOpen: true,
                    buttons: [
                        {
                            text: "OK",
                            click: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    ]
                });
            }
        })
    })
});
