<?php


namespace App\Model\Service;


use App;
use App\Model\Service;
use Core\Auth\DatabaseAuth;
use App\Model\Table\Game\Guild\MemberTable;

class GuildService extends Service
{
    /**
     * @var DatabaseAuth
     */
    private static $auth;

    /**
     * GuildService constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Guild\Guild");
        $this->loadModel("Game\Guild\Member");
        $this->loadModel("Game\Guild\Acces");
        self::$auth = new DatabaseAuth(App::getInstance()->getDb());
    }

    /**
     * @param $name
     * @param $user_id
     */
    function creer($name, $user_id){

        $this->GuildBase->create(array(
            "name" => $name,
            "createur_id" => $user_id
        ));

        $guild_id = App::getInstance()->getDb()->lasInsertId();

        $this->rejoindre($guild_id,$user_id,true);


    }

    /**
     * @param $guild_id
     * @param $user_id
     */
    function inviter($guild_id,$user_id){

        $this->InvitationBase->create(array(
            "guild_id" => $guild_id,
            "guest_id" => $user_id
        ));
    }


    /**
     * @param $guild_id
     * @param $user_id
     */
    function rejoindre($guild_id,$user_id, $manager = false ){

        if($this->MemberBase instanceof MemberTable) {
            $this->MemberBase->create(array(
                "guild_id" => $guild_id,
                "user_id" => $user_id,
                "is_manager" => $manager
            ));
        }
    }

    /**
     * @param $guild_id
     * @return bool
     */
    function isManager($guild_id){

        $appart = $this->MemberBase->findOneBy(array(

            "user_id" => self::$auth->getUser('id'),
            "guild_id" => $guild_id,

        ));

        if($appart->is_manager == true) return true ;

        $acces_manager = $this->AccesBase->existsBy(array(

            "player_id" => self::$auth->getUser('id'),
            "guild_id" => $guild_id,
            "val" => "privilege",
            "role" => "manage",
        ));

        return $acces_manager;
    }

}