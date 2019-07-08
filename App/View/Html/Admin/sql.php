<?php
echo $form ;

if(isset($result) && is_array($result)) {

?>
<table id="item-table" class="table table-bordered">
    <thead>
        <tr>
            <?php foreach ($result[0] as $col => $value ) {  ?>

                <td><?php echo $col ?></td>

            <?php }  ?>
        </tr>
    </thead>
    <tbody>
<?php foreach ($result as $index => $line) { ?>

        <tr>
        <?php foreach ($line as $col => $value) {  ?>

            <td><?php echo $value ?></td>

        <?php }  ?>
        </tr>

        <?php } ?>

    </tbody>
</table>

<?php } elseif( isset($result) && $result instanceof PDOStatement ) echo "executé...<br/>"; ?>