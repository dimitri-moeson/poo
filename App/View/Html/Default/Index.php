<?php

use Core\Database\Query;
?>
<div class="row">

    <div class="col-md-3">

    </div>

    <div class="col-md-9">
        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title">-+-</div>
            </div>

            <div class="panel-body"><?php

                echo Query::from('article','a')
                        ->where('id = 1')
                        ->select('id','titre','contenu') ;

            ?></div>
        </div>
    </div>

</div>
