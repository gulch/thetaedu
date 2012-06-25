<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Api extends Model{

    public function get_listof_all_lang()
    {    
        $q = mysql_real_escape_string($_GET['q']);
        $result = null;
        if($q != "")
        {
            $rez = DB::query(Database::SELECT, 'Select id, name FROM lang_list
                                                            WHERE UPPER(name) LIKE UPPER("'.$q.'%") ORDER BY name ASC')
                            ->as_object()->execute()->as_array();

            $result = json_encode($rez);
        }
        return $result;
    }

    public function get_listof_all_towns()
    {
        $q = mysql_real_escape_string($_GET['q']);
        $result = '';

        if($q != "")
        {
            $rez = DB::query(Database::SELECT, 'Select t.id,t.name, r.name as rname FROM town as t
                                                            LEFT JOIN region_list as r ON r.id = t.regionid
                                                            WHERE UPPER(t.name) LIKE UPPER("'.$q.'%") ORDER BY t.name ASC')
                ->as_object()
                ->execute();
            foreach ($rez as $r)
            {
                $result .= $r->name."|".$r->rname."|".$r->id."\n";
            }
        }
        return $result;
    }

    public function set_profile_image()
    {
        $filename = $this->_upload_img($_FILES['profileImage']['tmp_name'],$this->get_file_extension($_FILES['profileImage']['name']));
        $user = Auth::instance()->get_user();

        $ava = DB::select('avatar_image')->from('profiles')->where('userid','=',$user->id)->execute()->current();
        if($ava['avatar_image'])
        {
            unlink($_SERVER['DOCUMENT_ROOT'].'/media/uimg/'.$ava['avatar_image']);
        }
        if(DB::update('profiles')->value('avatar_image',$filename)->where('userid','=',$user->id)->execute())
        {
            return 'http://'.$_SERVER['SERVER_ADDR'].'/media/uimg/'.$filename;
        }
        else
        {
            return 'ERROR'; exit();
        }
    }

    public function get_file_extension($file_name)
    {
        return substr(strrchr($file_name,'.'),1);
    }

    public function _upload_img($file, $ext = NULL, $directory = NULL)
    {
        if($directory == NULL)
        {
            $directory = 'media/uimg';
        }

        if($ext== NULL)
        {
            $ext= 'jpg';
        }

        // Генерируем случайное название
        $symbols = '0123456789abcdefghijklmnopqrstuvwxyz';

        $filename = '';
        for($i = 0; $i < 10; $i++)
        {
            $filename .= rand(1, strlen($symbols));
        }

        // Изменение размера и загрузка изображения
        $im = Image::factory($file);

        if($im->width > $im->height)
        {
            $im->crop($im->height,$im->height);
        }
        else
        {
            $im->crop($im->width,$im->width);
        }
        $im->resize(200);
        $im->save("$directory/$filename.$ext");

        $im->resize(40);
        $im->save("$directory/small_.$filename.$ext");

        return "$filename.$ext";
    }

    public function save_profile()
    {
        $user = Auth::instance()->get_user();
        $username = mysql_real_escape_string($_POST['username']);
        $email = mysql_real_escape_string($_POST['pass']);
        $fullname = mysql_real_escape_string($_POST['fullname']);
        $birthday = mysql_real_escape_string($_POST['birthday']);
        $bio = mysql_real_escape_string($_POST['bio']);
        $sex = mysql_real_escape_string($_POST['sex']);
        $town = mysql_real_escape_string($_POST['town']);
        $townid = mysql_real_escape_string($_POST['townid']);

        $langArr = explode(';',$_POST['lang']);
        $contactArr = explode('=;=',mysql_real_escape_string($_POST['contact']));
        $contactTypeArr = explode(';',mysql_real_escape_string($_POST['contactype']));

        // save to table 'profile'
        if(!DB::query(Database::UPDATE,'UPDATE profiles SET fullname = "'.$fullname.'", birthday = "'.$birthday.'", bio = "'.$bio.'", sex = '.$sex.' WHERE userid = '.$user->id)->execute())

        //if(!DB::update('profiles')->set(array('fullname' => "$fullname",'birthday' => "$birthday",'bio' => "$bio",'sex' => $sex))->where('userid','=',$user->id)->execute())
        DB::query(Database::UPDATE,'UPDATE profiles SET fullname = "'.$fullname.'", birthday = "'.$birthday.'", bio = "'.$bio.'", sex = '.$sex.' WHERE userid = '.$user->id)->execute();

        if($town!='')
        {
            $trez = DB::select('id')->from('town')->where('name','=',$town)->execute()->current();

            if(!$trez["id"])
            {
                $irez = DB::insert('town',array('id','name'))->values(array(NULL,$town))->execute();
                $trez["id"] = $irez[0];
            }
            DB::delete('profile_town')->where('userid','=',$user->id)->execute();
            if(!DB::insert('profile_town',array('id','userid','townid'))->values(array(NULL,$user->id,$trez["id"]))->execute())
            {
                return 'ERROR';
            }
        }

        // add languages
        DB::delete('profile_languages')->where('userid','=',$user->id)->execute();
        foreach($langArr as $lang)
        {
            if($lang!='')
            {
                if(!DB::insert('profile_languages',array('id','userid','langid'))->values(array(NULL,$user->id,$lang))->execute())
                {
                    return 'ERROR';
                }
            }
        }
        // add contacts
        DB::delete('contacts')->where('userid','=',$user->id)->execute();
        $i = 0;
        while($contactArr[$i]!='' && $contactTypeArr[$i]!='')
        {
                if(!DB::insert('contacts',array('id','userid','conttypeid','value'))->values(array(NULL,$user->id,$contactTypeArr[$i],$contactArr[$i]))->execute())
                {
                    return 'ERROR';
                }
                $i++;
        }

        return 'OK';
    }

    public function follow_profile()
    {
        $user = Auth::instance()->get_user();
        $touser = mysql_real_escape_string($_POST['touser']);
        $rtype = mysql_real_escape_string($_POST['rtype']);

        $trez = DB::query(Database::SELECT,'SELECT id FROM relationships WHERE userid_to = '.$touser.' AND userid_from = '.$user->id)->execute()->current();
        if($trez["id"])
        {
            DB::query(Database::DELETE,'DELETE FROM relationships WHERE id = '.$trez['id'])->execute();
        }

        $q = 'INSERT INTO relationships(`id`,`userid_from`,`userid_to`,`datetime_from`, `relationship_typeid`) VALUES (NULL,'.$user->id.','.$touser.',"'.date('Y-m-d H:i:s').'",'.$rtype.')';
        //echo $q; exit();
        //if(!DB::insert('relationships',array('id','userid_from','userid_to','datetime_from','relationship_typeid'))->values(array(NULL,$user->id,$touser,date('Y-m-d H:i:s'),$rtype))->execute())
        if(!DB::query(Database::INSERT,$q)->execute())
        {
            return 'ERROR';
        }
        return 'OK';
    }

    public function unfollow_profile()
    {
        $user = Auth::instance()->get_user();
        $touser = mysql_real_escape_string($_POST['touser']);

        $trez = DB::query(Database::SELECT,'SELECT id FROM relationships WHERE userid_to = '.$touser.' AND userid_from = '.$user->id)->execute()->current();

        if($trez["id"])
        {
            if(!DB::query(Database::DELETE,'DELETE FROM relationships WHERE id = '.$trez['id'])->execute()) return 'ERROR';
        }
        return 'OK';
    }

    public function subscribe_profile()
    {
        $user = Auth::instance()->get_user();
        $touser = mysql_real_escape_string($_POST['touser']);

        $trez = DB::query(Database::SELECT,'SELECT id FROM subscribes WHERE usersubscribe = '.$touser)->execute()->current();

        if($trez["id"])
        {
            if(!DB::query(Database::DELETE,'DELETE FROM subscribes WHERE id = '.$trez['id'])->execute()) return 'ERROR';
        }
        else
        {
            if(!DB::query(Database::INSERT,'INSERT INTO subscribes(`id`,`usersubscribe`,`userid`,`datetime`) VALUES(NULL,'.$touser.','.$user->id.',"'.date('Y-m-d H:i:s').'")')->execute()) return 'ERROR';
        }
        return 'OK';
    }

    public function get_board_list($from)
    {
        $user = Auth::instance()->get_user();
        $mess = DB::query(Database::SELECT,'Select b.* FROM board as b
                                    WHERE b.userid = '.$user->id.' ORDER BY b.datetime DESC LIMIT '.$from.',25')
                                    ->execute()->as_array();
        return View::factory('boardslist',array('boards'=>$mess));
    }

    public function get_tasks_list($from)
    {
        $user = Auth::instance()->get_user();
        $mess = DB::query(Database::SELECT,'Select t.* FROM tasks as t
                                            WHERE t.foruserid = '.$user->id.' ORDER BY t.datetime DESC LIMIT '.$from.',25')
            ->execute()->as_array();
        return View::factory('taskslist',array('tasks'=>$mess));
    }

    //////// MESSAGES
    //-----
    public function get_messages_list($from)
    {
        $user = Auth::instance()->get_user();
        $mess = DB::query(Database::SELECT,'Select m.*, p.fullname FROM messages as m
                                            LEFT JOIN profiles as p ON p.userid = m.fromuserid
                                          WHERE m.touserid = '.$user->id.' ORDER BY m.createdatetime DESC LIMIT '.$from.',10')
                                        ->execute()->as_array();
        return View::factory('messageslist',array('messages'=>$mess));
    }

    public function message_checkread()
    {
        $user = Auth::instance()->get_user();
        $id = mysql_real_escape_string($_POST['id']);

        if(!DB::query(Database::UPDATE,'UPDATE messages SET isread = 1, readdatetime = "'.date('Y-m-d H:i:s').'" WHERE touserid='.$user->id.' AND id = '.$id)->execute()) return 'ERROR';
        return 'OK';
    }

    public function message_delete()
    {
        $user = Auth::instance()->get_user();
        $id = mysql_real_escape_string($_POST['uid']);

        DB::query(Database::DELETE,'DELETE FROM messages WHERE touserid='.$user->id.' AND id = '.$id)->execute();
        return 'OK';
    }

    public function message_send()
    {
        $user = Auth::instance()->get_user();
        $id = mysql_real_escape_string($_POST['uid']);
        $mtext = mysql_real_escape_string($_POST['mtext']);

        if(!DB::query(Database::INSERT,'INSERT INTO messages(`id`,`touserid`,`fromuserid`,`text`,`createdatetime`) values(NULL,'.$id.','.$user->id.',"'.$mtext.'","'.date('Y-m-d H:i:s').'")')->execute()) return 'ERROR';
        return 'OK';
    }
    //------

    ////////// SEARCH
    //--------
    public function get_all_publications_by_galuz($id,$from)
    {
        $user = Auth::instance()->get_user();

        $publ = DB::query(Database::SELECT,'Select p.id, p.title, p.createdate, p.published, g.name as galuzname, pr.name as predmetname From publications as p
                                                            left join galuz as g ON g.id = p.galuzid
                                                            left join predmet as pr ON pr.id = p.predmetid
                                                            WHERE p.galuzid = '.$id.' ORDER BY p.createdate DESC LIMIT '.$from.',25')->execute()->as_array();
        return View::factory('publicationslist',array('publications'=>$publ));
    }

    public function get_all_publications($from)
    {
        $user = Auth::instance()->get_user();

        $publ = DB::query(Database::SELECT,'Select p.id, p.title, p.createdate, p.published, g.name as galuzname, pr.name as predmetname From publications as p
                                                            left join galuz as g ON g.id = p.galuzid
                                                            left join predmet as pr ON pr.id = p.predmetid
                                                            WHERE p.published = 1 ORDER BY p.createdate DESC LIMIT '.$from.',25')->execute()->as_array();
        return View::factory('publicationslist',array('publications'=>$publ));
    }

    public function get_all_publications_by_predmet($id,$from)
    {
        $user = Auth::instance()->get_user();

        $publ = DB::query(Database::SELECT,'Select p.id, p.title, p.createdate, p.published, g.name as galuzname, pr.name as predmetname From publications as p
                                                            left join galuz as g ON g.id = p.galuzid
                                                            left join predmet as pr ON pr.id = p.predmetid
                                                            WHERE p.published = 1 AND p.predmetid = '.$id.' ORDER BY p.createdate DESC LIMIT '.$from.',25')->execute()->as_array();
        return View::factory('publicationslist',array('publications'=>$publ));
    }

    public function get_search_publications($s,$from)
    {
        $publ = DB::query(Database::SELECT,'Select p.id, p.title, p.createdate, p.published, g.name as galuzname, pr.name as predmetname From publications as p
                                                            left join galuz as g ON g.id = p.galuzid
                                                            left join predmet as pr ON pr.id = p.predmetid
                                                            WHERE p.published = 1 AND UPPER(p.title) LIKE UPPER("%'.$s.'%") ORDER BY p.createdate DESC LIMIT '.$from.',25')->execute()->as_array();
        return View::factory('publicationslist',array('publications'=>$publ));
    }

    public function get_search_users($s,$from)
    {
        $publ = DB::query(Database::SELECT,'Select p.id, p.fullname, p.userid, p.rating, p.avatar_image, p.birthday, t.name as townname From profiles as p
                                            left join profile_town as pt ON pt.userid = p.userid
                                            left join town as t ON t.id = pt.townid
                                            WHERE UPPER(p.fullname) LIKE UPPER("%'.$s.'%") ORDER BY p.rating DESC LIMIT '.$from.',25')->execute()->as_array();
        return View::factory('userslist',array('users'=>$publ));
    }
    //--------

    //////// PUBLICATIONS
    //--------
    public function publication_rate($pid)
    {
        $user = Auth::instance()->get_user();
        $value = (int)$_GET['rating_number'];
        $value *= 20;

        // check for voting
        $isvoted = DB::query(Database::SELECT,'SELECT id FROM publication_votes WHERE userid = '.$user->id.' AND publicationid = '.$pid)->execute()->count();

        if($isvoted>0) return __('youvotedalready');
        // get user rating
        $ur = DB::query(Database::SELECT,'SELECT rating FROM profiles WHERE userid = '.$user->id)->execute()->current();

        $usid = DB::query(Database::SELECT,'SELECT userid FROM publications WHERE id = '.$pid)->execute()->current();

        $usid = $usid['userid'];

        if($ur['rating']>100) $value = round(($value*(int)$ur['rating'])/100);

        if(DB::query(Database::INSERT,'INSERT INTO publication_votes(`id`,`userid`,`publicationid`,`datetime`,`value`) VALUES(NULL,'.$user->id.','.$pid.',"'.date('Y-m-d H:i:s').'",'.$value.')')->execute())
        {
            $newrating = DB::query(Database::SELECT,'SELECT SUM(value)/COUNT(id) as c FROM publication_votes WHERE publicationid = '.$pid)->execute()->current();

            DB::query(Database::UPDATE,'UPDATE publications SET rating = '.round($newrating['c']).' WHERE id = '.$pid)->execute();

            $newuserrating = DB::query(Database::SELECT,'SELECT SUM(rating)/COUNT(id) as c FROM publications WHERE published = 1 AND userid = '.$usid)->execute()->current();

            DB::query(Database::UPDATE,'UPDATE profiles SET rating = '.round($newuserrating['c']).' WHERE userid = '.$usid)->execute();
        }

        return __('thanksforvote');
    }

    public function get_predmets()
    {
        $id = mysql_real_escape_string($_POST['id']);
        $pid = mysql_real_escape_string($_POST['pid']);

        $predmet = DB::query(Database::SELECT,'SELECT id,name FROM predmet WHERE galuzid = '.$id)->execute()->as_array();

        $rez = '<select id="predmet" name="predmet" placeholder="'.__('input.predmet').'" required="" tabindex="3">';
        foreach($predmet as $g)
        {
            if($pid == $g['id'])
            {
                $rez .= '<option selected="selected" value="'.$g['id'].'">'.$g['name'].'</option>';
            }
            else
            {
                $rez .= '<option value="'.$g['id'].'">'.$g['name'].'</option>';
            }
        }
        return $rez.'</select>';
    }

    public function publication_save()
    {
        $pid = mysql_real_escape_string($_POST['pid']);
        $title = mysql_real_escape_string($_POST['title']);
        $galuz = mysql_real_escape_string($_POST['galuz']);
        $predmet = mysql_real_escape_string($_POST['predmet']);
        $text = str_replace("\n", "", $_POST['ptext']);
        $text = str_replace("\r", "", $text);
        $text = mysql_real_escape_string($text);

        $foruserid = explode(';',mysql_real_escape_string($_POST['foruserid']));
        $tasktext = explode('=;=',mysql_real_escape_string($_POST['tasktext']));
        $publish = explode(';',mysql_real_escape_string($_POST['publish']));

        $user = Auth::instance()->get_user();

        // save to table 'profile'
        if(!DB::query(Database::UPDATE,'UPDATE publications SET title = "'.$title.'", galuzid = '.$galuz.', predmetid = '.$predmet.', text = "'.$text.'", modifydate = "'.date('Y-m-d H:i:s').'" WHERE userid = '.$user->id.' AND id = '.$pid)->execute()) return 'ERROR';


        // add contacts
        $i = 0;
        $newtasks = array();
        while($foruserid[$i]!='' && $tasktext[$i]!='' && $publish[$i]!='')
        {
            $position = $i+1;
            if(DB::query(Database::SELECT,'SELECT id FROM tasks WHERE foruserid = '.$foruserid[$i].' AND publicationid = '.$pid)->execute()->count() > 0)
            {
                if(!DB::query(Database::UPDATE,'UPDATE tasks SET title = "'.$tasktext[$i].'",datetime = "'.date('Y-m-d H:i:s').'",position = '.$position.',published = '.$publish[$i].' WHERE publicationid = '.$pid.' AND foruserid = '.$foruserid[$i])->execute())
                {
                    return 'ERROR';
                }
                $newtasks[] = $foruserid[$i];
            }
            else
            {
                if(!DB::query(Database::INSERT,'INSERT INTO tasks(`id`,`foruserid`,`publicationid`,`title`,`datetime`,`position`,`published`) VALUES(NULL,'.$foruserid[$i].','.$pid.',"'.$tasktext[$i].'","'.date('Y-m-d H:i:s').'",'.$position.','.$publish[$i].')')->execute())
                {
                    return 'ERROR';
                }
                $newtasks[] = $foruserid[$i];
            }

            $nt = DB::query(Database::SELECT,'SELECT t.id, t.title, pro.fullname, pro.userid FROM tasks as t
                                                LEFT JOIN publications as p ON p.id = t.publicationid
                                                LEFT JOIN profiles as pro ON pro.userid = p.userid WHERE t.publicationid  = '.$pid.' AND t.foruserid = '.$foruserid[$i].' ORDER BY t.datetime DESC')->execute()->current();


            $mess = __('havenewtask').' "'.HTML::anchor('publication/edit_task/'.$nt['id'],$nt['title']).'" '.__('fromuser').' '.HTML::anchor('uid'.$nt['userid'],$nt['fullname']);
            DB::query(Database::INSERT,'INSERT INTO board(`id`,`userid`,`datetime`,`message`) VALUES(NULL,'.$foruserid[$i].',"'.date('Y-m-d H:i:s').'","'.mysql_real_escape_string($mess).'")')->execute();


            $i++;
        }

        DB::query(Database::DELETE,'DELETE FROM tasks WHERE publicationid = '.$pid.' AND foruserid NOT IN('.implode(',',$newtasks).')')->execute();

        return 'OK';
    }

    public function publication_new()
    {
        $title = mysql_real_escape_string($_POST['title']);
        $galuz = mysql_real_escape_string($_POST['galuz']);
        $predmet = mysql_real_escape_string($_POST['predmet']);
        $text = str_replace("\n", "", $_POST['ptext']);
        $text = str_replace("\r", "", $text);
        $text = mysql_real_escape_string($text);

        $foruserid = explode(';',mysql_real_escape_string($_POST['foruserid']));
        $tasktext = explode('=;=',mysql_real_escape_string($_POST['tasktext']));
        $publish = explode(';',mysql_real_escape_string($_POST['publish']));

        $user = Auth::instance()->get_user();

        // save to table 'profile'
        if(!$insid = DB::query(Database::INSERT,'INSERT INTO publications(`id`,`userid`,`title`,`galuzid`,`predmetid`,`text`,`createdate`) VALUES(NULL,'.$user->id.',"'.$title.'",'.$galuz.','.$predmet.',"'.$text.'","'.date('Y-m-d H:i:s').'")')->execute()) return 'ERROR';
        // add contacts
        $i = 0;
        $newtasks = array();
        while($foruserid[$i]!='' && $tasktext[$i]!='' && $publish[$i]!='')
        {
            $position = $i+1;
                if(!DB::query(Database::INSERT,'INSERT INTO tasks(`id`,`foruserid`,`publicationid`,`title`,`datetime`,`position`,`published`) VALUES(NULL,'.$foruserid[$i].','.$insid[0].',"'.$tasktext[$i].'","'.date('Y-m-d H:i:s').'",'.$position.','.$publish[$i].')')->execute())
                {
                    return 'ERROR';
                }
            $nt = DB::query(Database::SELECT,'SELECT t.id, t.title, pro.fullname, pro.userid FROM tasks as t
                                                LEFT JOIN publications as p ON p.id = t.publicationid
                                                LEFT JOIN profiles as pro ON pro.userid = p.userid WHERE t.publicationid  = '.$insid[0].' AND t.foruserid = '.$foruserid[$i].' ORDER BY t.datetime DESC')->execute()->current();


            $mess = __('havenewtask').' "'.HTML::anchor('publication/edit_task/'.$nt['id'],$nt['title']).'" '.__('fromuser').' '.HTML::anchor('uid'.$nt['userid'],$nt['fullname']);
            DB::query(Database::INSERT,'INSERT INTO board(`id`,`userid`,`datetime`,`message`) VALUES(NULL,'.$foruserid[$i].',"'.date('Y-m-d H:i:s').'","'.mysql_real_escape_string($mess).'")')->execute();

            $i++;
        }
        return URL::site('publication/view/'.$insid[0]);
    }

    public function publication_publish()
    {
        $user = Auth::instance()->get_user();
        $id = mysql_real_escape_string($_POST['id']);

        if(!DB::query(Database::UPDATE,'UPDATE publications SET published = 1 WHERE userid='.$user->id.' AND id = '.$id)->execute()) return 'ERROR';
        return 'OK';
    }
    public function task_cancel()
    {
        $user = Auth::instance()->get_user();
        $id = mysql_real_escape_string($_POST['id']);

        if(!DB::query(Database::UPDATE,'UPDATE tasks SET canceled = 1 WHERE foruserid='.$user->id.' AND id = '.$id)->execute()) return 'ERROR';
        return 'OK';
    }

    public function task_save()
    {
        $user = Auth::instance()->get_user();
        $id = mysql_real_escape_string($_POST['id']);
        $text = mysql_real_escape_string($_POST['text']);
        $done = mysql_real_escape_string($_POST['done']);

        if(!DB::query(Database::UPDATE,'UPDATE tasks SET text = "'.$text.'", done = '.$done.' WHERE foruserid='.$user->id.' AND id = '.$id)->execute()) return 'ERROR';
        return 'OK';
    }
}