<?php
    $coord = array(

        1 => array(

            1 => array( "char" => "&nwArr;" , "x" => "n" , "y" => "w"),
            2 => array( "char" => "&uArr;"  , "x" => "n" , "y" => "x"),
            3 => array( "char" => "&neArr;" , "x" => "n" , "y" => "e"),
        ),

        2 => array(

            1 => array( "char" => "&lArr;" , "x" => "x" , "y" => "w"),
            2 => array( "char" => "&nbsp;" , "x" => "x" , "y" => "x"),
            3 => array( "char" => "&rArr;" , "x" => "x" , "y" => "e"),
        ),

        3 => array(

            1 => array( "char" => "&swArr;" , "x" => "s" , "y" => "w"),
            2 => array( "char" => "&dArr;"  , "x" => "s" , "y" => "x"),
            3 => array( "char" => "&seArr;" , "x" => "s" , "y" => "e"),
        ),
    );
?>
<form method="post" class="">
    <input type="hidden" name="move" value="1">

<?php foreach ($coord as $x => $dev ){ ?>

    <div class="btn-group">

        <?php foreach ($dev as $y => $val ){ ?>

            <?php if($x == 2 && $y == 2) { ?>

                <button class=" <?php //echo $y%2 === 1 ? "3" : "6" ?>  btn btn-warning" type="button">
                    <?php echo /* $center */ $val["char"] ?>
                </button>

            <?php } else { ?>

                <button class="<?php //echo $y%2 === 1 ? "3" : "6" ?> btn btn-info" type="submit" name="coordonnees" value="<?php echo $val["x"] ?>|<?php echo $val["y"] ?>">
                    <?php echo $val["char"] ?>
                </button>

            <?php } ?>

        <?php } ?>

    </div>

<?php } ?>

</form>
