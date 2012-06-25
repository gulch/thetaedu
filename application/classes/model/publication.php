<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Publication extends Model{

    public function get_publication_for_view($pid)
    {
        // get user main info
        $user = Auth::instance()->get_user();

        $dbrez = DB::query(Database::SELECT,'SELECT p.id, p.rating, pro.fullname as authors, p.userid, p.title, p.text as ptext, p.createdate, p.modifydate, p.published, g.name as galuzname, pr.name as predmetname FROM publications as p
                                             LEFT join galuz as g ON g.id = p.galuzid
                                             LEFT JOIN predmet as pr ON pr.id = p.predmetid
                                             LEFT JOIN profiles as pro ON pro.userid = p.userid
                                             WHERE p.id = '.$pid)->execute()->as_array();
        $rez = $dbrez[0];

        $ismine = FALSE;
        if($rez['userid'])
        {
            if($rez['userid'] == $user->id) $ismine = TRUE;
        }
        if($rez['published']==0 && $ismine==FALSE) return null;
        $rez['ismine'] = $ismine;

        $rez['authors'] = HTML::anchor('uid'.$rez['userid'],$rez['authors']);

        $anoa = DB::query(Database::SELECT,'Select t.text, t.position, p.fullname, p.userid FROM tasks as t
                                                        LEFT JOIN profiles as p ON p.userid = t.foruserid
                                                        WHERE t.publicationid = '.$pid.' AND t.published = 1  ORDER BY t.position ASC')->execute()->as_array();

        if($anoa)
        {
            foreach($anoa as $aa)
            {
                $rez['authors'] .= ','.HTML::anchor('uid'.$aa['userid'],$aa['fullname']);
                $rez['ptext'] .= '<br/>'.$aa['text'];
            }
        }
        return $rez;
    }

    public function get_publication_for_edit($pid)
    {
        // get user main info
        $user = Auth::instance()->get_user();

        $dbrez = DB::query(Database::SELECT,'SELECT p.id, p.rating, pro.fullname as authors, p.userid, p.title, p.text as ptext, p.createdate, p.modifydate, p.published, p.galuzid, p.predmetid, g.name as galuzname, pr.name as predmetname FROM publications as p
                                             LEFT join galuz as g ON g.id = p.galuzid
                                             LEFT JOIN predmet as pr ON pr.id = p.predmetid
                                             LEFT JOIN profiles as pro ON pro.userid = p.userid
                                             WHERE p.id = '.$pid)->execute()->as_array();
        $rez = $dbrez[0];

        $ismine = FALSE;
        if($rez['userid'])
        {
            if($rez['userid'] == $user->id) $ismine = TRUE;
        }
        if($ismine==FALSE) Request::$current->redirect('error/400');

        $rez['authors'] = HTML::anchor('uid'.$rez['userid'],$rez['authors']);
        $rez['galuz'] = DB::query(Database::SELECT,'SELECT id, name FROM galuz')->execute()->as_array();

        $rez['tasks'] = DB::query(Database::SELECT,'SELECT t.*, p.fullname FROM tasks as t
                                                    LEFT JOIN profiles as p ON p.userid = t.foruserid
                                                    WHERE t.publicationid = '.$rez['id'].' ORDER BY t.position ASC')->execute()->as_array();

        $rez['following'] = DB::query(Database::SELECT,'SELECT rt.name as rtype, p.userid, p.fullname FROM relationships as r
                                                        LEFT JOIN relationship_types as rt ON rt.id = r.relationship_typeid
                                                        LEFT JOIN profiles as p ON p.userid = r.userid_from
                                                        WHERE r.userid_to = '.$user->id)->execute()->as_array();

        return $rez;
    }

    public function get_publication_for_new()
    {
        $user = Auth::instance()->get_user();
        $rez = array();
        $rez['galuz'] = DB::query(Database::SELECT,'SELECT id, name FROM galuz')->execute()->as_array();
        $rez['following'] = DB::query(Database::SELECT,'SELECT rt.name as rtype, p.userid, p.fullname FROM relationships as r
                                                        LEFT JOIN relationship_types as rt ON rt.id = r.relationship_typeid
                                                        LEFT JOIN profiles as p ON p.userid = r.userid_from
                                                        WHERE r.userid_to = '.$user->id)->execute()->as_array();

        return $rez;
    }

    public function delete_publication($pid)
    {
        $user = Auth::instance()->get_user();
        DB::query(Database::DELETE,'DELETE FROM publications WHERE userid = '.$user->id.' AND id = '.$pid)->execute();
    }

    public function edit_task($pid)
    {
        // get user main info
        $user = Auth::instance()->get_user();

        $dbrez = DB::query(Database::SELECT,'Select t.id, t.title, t.text, t.done, p.title as publicationtitle FROM tasks as t
                                            LEFT JOIN publications as p ON p.id = t.publicationid
                                        WHERE t.foruserid = '.$user->id.' AND t.id = '.$pid)->execute()->as_array();
        if(!$dbrez[0]) Request::$current->redirect('error/400');

        return $dbrez[0];
    }
}