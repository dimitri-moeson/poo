<?php

// use Core\Database\Query;
?>
<div class="row">

    <div class="col-md-3">

    </div>

    <div class="col-md-9">
        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo $page->titre ?></div>
            </div>

            <div class="panel-body">

                <?php echo $page->contenu ?>

                <a href="/<?php echo $page->slug ?>">reload</a>
            </div>
        </div>
    </div>

    <pre><?php var_dump($page) ?></pre>

</div>
