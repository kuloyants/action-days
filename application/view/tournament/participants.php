<?php
?>
<h2><?=$lang['participants.title']?></h2>
<hr class="colored">
<div class="row content-row">
    <div class="col-lg-12">
        <div class="slider-buttons portfolio-filter">
            <ul id="filters" class="clearfix">
                <li>
                    <span class="filter active" data-filter="main"><?=$lang['main_tournament']?></span>
                </li>
                <li>
                    <span class="filter" data-filter="seeded"><?=$lang['seeded_players']?></span>
                </li>
                <li>
                    <span class="filter" data-filter="doubles_cup"><?=$lang['doubles_cup']?></span>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="sliderlist">
    <div class="content main active" data-cat="main" data-toggle="modal">
        <? include('participants/main.php') ?>
    </div>
    <div class="content seeded" data-cat="seeded" data-toggle="modal">
        <? include('participants/seeded.php') ?>
    </div>
    <div class="content doubles_cup" data-cat="doubles_cup" data-toggle="modal">
        HIER STEHEH DIE doubles cup Spieler
    </div>
</div>

