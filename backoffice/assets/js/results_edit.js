$(document).ready(function () {
    $("[data-role='edit']").on("click", function(event) {
        event.preventDefault(); // prevent default submit behaviour
        var data = {};
        $match = $(this).closest("tr");
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
        data.walkover           = '';

        $match.find("[data-role=score]").each(function (){
            if ($(this).find("select").val() == $match.data("raceto")) {
                var winnerSelector = "select[name='" + $(this).data("player") + "']";
                data.winner = $match.find(winnerSelector).val();
                data.winnerToMatch = $match.data("winnertomatch");
                data.winnerToSlot = $match.data("winnertoslot");
            }
        });

        if (data.player1_id == "walkover") {
            data.winner = $match.find("select[name='player2']").val();
            data.winnerToMatch = $match.data("winnertomatch");
            data.winnerToSlot = $match.data("winnertoslot");
            data.walkover = "1";
        }
        if (data.player2_id == "walkover") {
            data.winner = $match.find("select[name='player1']").val();
            data.winnerToMatch = $match.data("winnertomatch");
            data.winnerToSlot = $match.data("winnertoslot");
            data.walkover = "2";
        }

        var switchBetweenLoaderAndButton = function(){
            $match.find('.buttonConfirm').toggleClass("hidden");
            $match.find('#waitTillConfirmed').toggleClass("hidden");
        };

        $.ajax({
            url: "?section=results",
            type: "POST",
            data: data,
            beforeSend: function(){
                switchBetweenLoaderAndButton();
            },
            complete: function(){
                switchBetweenLoaderAndButton();
            },
            success: function(response) {
                window.location.reload(true)
            }
        })
    });
});
