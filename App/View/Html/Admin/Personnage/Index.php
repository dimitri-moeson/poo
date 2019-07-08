<table id="item-table" class="table">

    <?php use App\Model\Entity\Game\Personnage\PersonnageEntity; ?>
            <thead>

                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Histoire</th>
                    <th>Classe</th>
                    <th>Statut</th>
                    <th>Vie</th>
                    <th>Genre</th>
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

                <td><?php foreach($personnage->getStats()->getContainer() as $stats){ ?> <?php echo $stats->getName();} ?></td>

            <?php } ?>
        </tr>

<?php } ?>
    </tbody>
    </table>
