<table id="item-table" class="table">

    <?php use App\Model\Entity\Game\Personnage\PersonnageEntity;
    use Core\Render\Render; ?>
            <thead>

                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Histoire</th>
                    <th>Classe</th>
                    <th>Statut</th>
                    <th>Vie</th>
                    <th>Genre</th>
                    <th>Joueur</th>
                </tr>

            </thead>
            <tbody>
    <?php foreach($personnages as $personnage) { ?>
        <tr>
            <?php if( $personnage instanceOf PersonnageEntity ) { ?>

                <td><?php echo $personnage->id ?></td>
                <td><?php echo $personnage->getName() ?></td>
                <td><?php echo $personnage->getDescription() ?></td>
                <td><?php echo $personnage->getType() ?></td>
                <td><?php echo $personnage->getStatus() ?></td>
                <td><?php echo $personnage->getVie() ?></td>
                <td><?php echo $personnage->getSexe() == 2 ? "Femme" : "Homme" ?></td>
                <td><?php echo @$User->find($personnage->user_id)->login ?></td>
                <td><?php foreach($personnage->getStats()->getContainer() as $stats){ ?>
                        <?php echo $stats->getName(); ?>
                    <?php } ?></td>
                <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                        "p" => "personnage",
                        "id" => $personnage->id

                    )); ?></td>
            <?php } ?>
        </tr>

<?php } ?>
    </tbody>
    </table>
