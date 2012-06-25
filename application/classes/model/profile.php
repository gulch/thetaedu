<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Profile extends Model{

    public function create_profile()
    {
        $user = Auth::instance()->get_user();
        if(DB::insert('profiles')
            ->columns(array('id','userid'))
            ->values(array(NULL,$user->id))
            ->execute()) return TRUE;
        return FALSE;
    }

    public function is_profile_record()
    {
        $user = Auth::instance()->get_user();
        if(DB::select('id')->from('profiles')->where('userid','=',$user->id)->as_object()->execute()->count() > 0) return TRUE;
        return FALSE;
    }

    public function get_profile_for_edit()
    {
        // get user main info
       $user = Auth::instance()->get_user();
       $dbrez = DB::query(Database::SELECT,'SELECT u.username, u.email, p.*, town.`name` as town, t.townid FROM users as u
                                    LEFT JOIN profiles as p ON p.userid = u.id
                                    LEFT JOIN profile_town as t ON t.userid = u.id
                                    LEFT JOIN town ON town.id = t.townid
                                    WHERE u.id = '.$user->id)->execute()->as_array();
        $rez = $dbrez[0];

        // get list of contact types
        $rez['contactypes'] = $this->get_listof_contacttypes();

        // get user contacts
        $rez['usercontacts'] = DB::select()->from('contacts')->where('userid','=',$user->id)->order_by('conttypeid','ASC')->execute()->as_array();

        // get user languages
        $rez['userlanguages'] = DB::query(Database::SELECT,'SELECT ll.id, ll.`name` FROM profile_languages as pl
                                            LEFT JOIN lang_list as ll ON ll.id = pl.langid
                                            WHERE pl.userid = '.$user->id)->execute()->as_array();

       // var_dump($rez); exit();
       return $rez; 
    }

    public function get_profile_for_view($id)
    {
        // get user main info
        $user = Auth::instance()->get_user();

        if(!$id)
        {
            $id = $user->id;
            $ismine = TRUE;
        }
        else
        {   if($user->id == $id)
            {
                $ismine = TRUE;
            }
            else
            {
                $ismine = FALSE;
            }
        }

        $dbrez = DB::query(Database::SELECT,'SELECT u.username, u.email, p.*, town.`name` as town, t.townid FROM users as u
                                    LEFT JOIN profiles as p ON p.userid = u.id
                                    LEFT JOIN profile_town as t ON t.userid = u.id
                                    LEFT JOIN town ON town.id = t.townid
                                    WHERE u.id = '.$id)->execute()->as_array();
        $rez = $dbrez[0];

        // get user contacts
        $rez['usercontacts'] = DB::query(Database::SELECT,'SELECT c.id, c.`value`, ct.ico_image, ct.`name` FROM contacts as c
                                         LEFT JOIN contact_type as ct ON ct.id = c.conttypeid
                                         WHERE c.userid ='.$id)->execute()->as_array();

        // get user languages
        $rez['userlanguages'] = DB::query(Database::SELECT,'SELECT ll.id, ll.`name`,ll.`ico_image` FROM profile_languages as pl
                                            LEFT JOIN lang_list as ll ON ll.id = pl.langid
                                            WHERE pl.userid = '.$id)->execute()->as_array();

        $rez['relationshiptypes'] = DB::query(Database::SELECT,'SELECT id, `name`,`ico_image` FROM relationship_types')->execute()->as_array();
        $rez['ismine'] = $ismine;
        $rez['uid'] = $id;


        // get user friends list
        $rez['following']=DB::query(Database::SELECT,'Select p.userid, p.fullname, p.avatar_image, rt.name as typename From relationships as r
                                    left join relationship_types as rt ON rt.id = r.relationship_typeid
                                    left join profiles as p ON p.userid = r.userid_to
                                    WHERE r.userid_from = '.$id.' ORDER by r.id DESC')->execute()->as_array();

        $rez['followers']=DB::query(Database::SELECT,'Select p.userid, p.fullname, p.avatar_image, rt.name as typename From relationships as r
                                    left join relationship_types as rt ON rt.id = r.relationship_typeid
                                    left join profiles as p ON p.userid = r.userid_from
                                    WHERE r.userid_to = '.$id.' ORDER by r.id DESC')->execute()->as_array();

        $rez['followers_count'] = DB::query(Database::SELECT,'Select r.userid_to From relationships as r
                                                              WHERE r.userid_to = '.$id)->execute()->count();
        $rez['following_count'] = DB::query(Database::SELECT,'Select r.userid_from From relationships as r
                                                              WHERE r.userid_from = '.$id)->execute()->count();

        $rez['publications'] = DB::query(Database::SELECT,'Select p.id, p.title, p.createdate, p.published, g.name as galuzname, pr.name as predmetname From publications as p
                                                            left join galuz as g ON g.id = p.galuzid
                                                            left join predmet as pr ON pr.id = p.predmetid
                                                            WHERE p.userid = '.$id.' ORDER BY p.createdate DESC')->execute()->as_array();
        $rez['subscribes'] = DB::query(Database::SELECT,'Select s.*, p.fullname, pr.name as kursname FROM subscribes as s
                                                LEFT JOIN profiles as p ON p.userid = s.usersubscribe
                                                LEFT JOIN predmet as pr ON pr.id = s.kurssubscribe
                                                WHERE s.userid = '.$id.' ORDER BY s.datetime DESC')->execute()->as_array();

        if(!$ismine)
        {
            // get user relationship
            $rez['relationship'] = DB::query(Database::SELECT,'SELECT rt.`name` from relationships as r
                                        LEFT JOIN relationship_types as rt on rt.id = r.relationship_typeid
                                        WHERE r.userid_from = '.$user->id.' AND r.userid_to = '.$id)->execute()->as_array();

            $rez['subscribe'] = DB::query(Database::SELECT,'SELECT id from subscribes where usersubscribe = '.$id.' AND userid = '.$user->id)->execute()->current();

        }
        //var_dump($rez); exit();
        return $rez;
    }
    
    public function get_listof_contacttypes()
    {
        return DB::select()->from('contact_type')->order_by('name','ASC')->execute()->as_array();
    }

    public function get_profile_menu()
    {
        $user = Auth::instance()->get_user();
        return DB::select()->from('profiles')->where('userid','=',$user->id)->execute()->current();
    }
}