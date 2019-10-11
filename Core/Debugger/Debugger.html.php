<?php

use App\Model\Entity\Game\Personnage\PersonnageEntity;
use Core\Debugger\Debugger;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;

?>

<?php if($this instanceof Debugger){ ?>
<script src="/debug/js"></script>
<link rel="stylesheet" href="/debug/css" crossorigin="anonymous">

<div id="sql-debug" class="debug">
    <div class="table-responsive">

        <table class="table table-striped table-bordered table-hover table-condensed">

            <thead>
                <tr>
                    <th>Request(<?php echo $this->countSql() ?>)</th>
                    <th>Attrib</th>
                    <th>File</th>
                    <th>Function</th>
                    <th>line</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($this->getSql() as $log) { ?>

                <tr>
                    <td class="info" rowspan="<?php echo $this->getLimitTrace() ?> "><?php echo $log["content"] ?></td>
                    <td class="info" rowspan="<?php echo $this->getLimitTrace() ?> "><pre><?php var_dump($log["attrib"]) ?></pre></td>
                    <td><?php echo $this->protect_file($log["trace"][0]["file"]) ?></td>
                    <td><?php echo($log["trace"][0]["function"]) ?></td>
                    <td><?php echo($log["trace"][0]["line"]) ?></td>
                </tr>

                <?php for ($i = 1; $i < $this->getLimitTrace(); $i++) { ?>

                    <tr>
                        <td><?php echo $this->protect_file( $log["trace"][$i]["file"]) ?></td>
                        <td><?php echo($log["trace"][$i]["function"]) ?></td>
                        <td><?php echo($log["trace"][$i]["line"]) ?></td>
                    </tr>

                <?php } ?>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="log-debug" class="debug">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">

            <thead>

                <tr>

                    <th>Content</th>
                    <th>File</th>
                    <th>Function</th>
                    <th>line</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach ($this->getHistory() as $log) { ?>

                <tr>
                    <td class="info" rowspan="<?php echo $this->getLimitTrace() ?> "><pre><?php var_dump($log["content"]) ?></pre></td>
                    <td><?php echo $this->protect_file( $log["trace"][0]["file"]) ?></td>
                    <td><?php echo($log["trace"][0]["function"]) ?></td>
                    <td><?php echo($log["trace"][0]["line"]) ?></td>
                </tr>

                <?php for ($i = 1; $i < $this->getLimitTrace(); $i++) { ?>

                    <tr>
                        <td><?php echo $this->protect_file($log["trace"][$i]["file"]) ?></td>
                        <td><?php echo($log["trace"][$i]["function"]) ?></td>
                        <td><?php echo($log["trace"][$i]["line"]) ?></td>
                    </tr>

                <?php } ?>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="post-debug" class="debug">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">

            <thead>

            <tr>

                <th>var</th>
                <th>content</th>

            </tr>

            </thead>

            <tbody>

            <?php foreach (Post::getInstance()->content() as $var => $content) { ?>

                <tr>
                    <td><?php echo($var) ?></td>
                    <td><pre><?php var_dump($content) ?></pre></td>
                </tr>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="session-debug" class="debug">
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed">

        <thead>

        <tr>

            <th>var</th>
            <th>content</th>

        </tr>

        </thead>

        <tbody>

        <?php foreach ($_SESSION as $var => $content) { ?>

            <tr>
                <td><?php echo($var) ?></td>
                <td><pre><?php var_dump($content) ?></pre></td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
</div>
</div>

<div id="get-debug" class="debug">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">

            <thead>

            <tr>

                <th>var</th>
                <th>content</th>

            </tr>

            </thead>

            <tbody>

            <?php foreach (Get::getInstance()->content('s') as $var => $content) { ?>

                <tr>
                    <td><?php echo($var) ?></td>
                    <td><pre><?php var_dump($content) ?></pre></td>
                </tr>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="server-debug" class="debug">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">

            <thead>

            <tr>

                <th>var</th>
                <th>content</th>

            </tr>

            </thead>

            <tbody>

            <?php foreach ($_SERVER as $var => $content) { ?>

                <tr>
                    <td><?php echo($var) ?></td>
                    <td><pre><?php var_dump($content) ?></pre></td>
                </tr>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="app-debug" class="debug" >
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">

            <thead>

            <tr>

                <th>var</th>
                <th>content</th>

            </tr>

            </thead>

            <tbody>

            <?php foreach ($this->getAppli() as $var => $content) { ?>

                <tr>
                    <td><?php echo($var) ?></td>
                    <td><pre><?php var_dump($content) ?></pre></td>
                </tr>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="request-debug" class="debug" >
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-condensed">

        <thead>

        <tr>

            <th>var</th>
            <th>content</th>

        </tr>

        </thead>

        <tbody>

        <?php foreach (\Core\Request\Request::getInstance() as $var => $content) { ?>

            <tr>
                <td><?php echo($var) ?></td>
                <td><pre><?php var_dump( \Core\Request\Request::getInstance()->{"get".ucfirst($var)."()"}) ?></pre></td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
</div>
</div>

<?php if($this->getPersonnage() != null ) {

        $perso = $this->getPersonnage() ;

        if ($perso instanceof PersonnageEntity) { ?>
            <div id="perso-debug" class="debug well">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed">

                        <thead>

                            <tr>

                                <th>var</th>
                                <th>content</th>

                            </tr>

                        </thead>

                        <tbody>
                            <tr>
                                <th>nom</th>
                                <td>
                                    <pre><?php var_dump($perso->getName()) ?></pre>
                                </td>
                            </tr>
                            <tr>
                                <th>classe</th>
                                <td>
                                    <pre><?php var_dump($perso->getType()) ?></pre>
                                </td>
                            </tr>
                            <tr>
                                <th>vie</th>
                                <td>
                                    <pre><?php var_dump($perso->getVie()) ?></pre>
                                </td>
                            </tr>

                            <tr>
                                <th>stats</th>
                                <td><?php foreach ($perso->getStats()->getContainer() as $savoir) { ?>
                                        <?php //if($savoir instanceof \App\Model\Entity\Game\Item\ItemEntity){ ?>
                                        <li><?php echo $savoir->getName() ?> // <?php echo $savoir->getVal() ?></li>
                                        <?php //} ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <th>position</th>
                                <td>
                                    <pre><?php var_dump($perso->getPosition()) ?></pre>
                                </td>
                            </tr>
                            <tr>
                                <th>savoir</th>
                                <td> <?php foreach ($perso->getKnows()->getContainer() as $savoir) { ?>
                                        <?php // if($savoir instanceof \App\Model\Entity\Game\Item\ItemEntity){ ?>
                                        <li><?php echo $savoir->getName() ?> // <?php echo $savoir->getVal() ?></li>
                                        <?php // } ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <th>sac</th>
                                <td> <?php foreach ($perso->getInventaire()->getContainer() as $savoir) { ?>
                                        <?php // if($savoir instanceof \App\Model\Entity\Game\Item\ItemEntity){ ?>
                                        <li><?php echo $savoir->getName() ?> // <?php echo $savoir->getVal() ?></li>
                                        <?php // } ?>
                                    <?php } ?></td>
                            </tr>

                            <tr>
                                <th>grimoire</th>
                                <td> <?php foreach ($perso->getSpellBook()->getContainer() as $savoir) { ?>
                                        <?php // if($savoir instanceof \App\Model\Entity\Game\Item\ItemEntity){ ?>
                                        <li><?php echo $savoir->getName() ?> // <?php echo $savoir->getVal() ?></li>
                                        <?php // } ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <th>quetes</th>
                                <td> <?php foreach ($perso->getQuestBook()->getContainer() as $savoir) { ?>
                                        <?php // if($savoir instanceof \App\Model\Entity\Game\Item\ItemEntity){ ?>
                                        <li><?php echo $savoir->getName() ?> // <?php echo $savoir->getVal() ?></li>
                                        <?php // } ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <th>all</th>
                                <td>
                                    <pre><?php var_dump($perso) ?></pre>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
<?php
        }

    }
?>

<div id="debug-bar">
    <a onclick="showDebug('app');" class="btn btn-info ">App</a>
    <a onclick="showDebug('log');" class="btn btn-info ">Logs</a>
    <a onclick="showDebug('get');" class="btn btn-info">get</a>
    <a onclick="showDebug('sql');" class="btn btn-info">sql</a>
    <a onclick="showDebug('post');" class="btn btn-info">post</a>
<?php if(isset($perso)){ ?>
    <a onclick="showDebug('perso');" class="btn btn-info">perso</a>
<?php } ?>
    <a onclick="showDebug('server');" class="btn btn-info">server</a>
    <a onclick="showDebug('session');" class="btn btn-info">session</a>
    <a onclick="showDebug('request');" class="btn btn-info">request</a>

</div>

<!-- end debug -->
<?php } ?>