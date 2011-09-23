<?php
/**
 * Login
 *
 * Copyright 2010 by Shaun McCormick <shaun+login@modx.com>
 *
 * Login is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Login is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Login; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * Shows a list of active, logged-in users
 *
 * @package login
 * @subpackage controllers
 */
class LoginActiveUsersController extends LoginController {
    /** @var string $username */
    public $username;
    /** @var string $password */
    public $password;
    /** @var modUser $user */
    public $user;

    public function initialize() {
        $this->setDefaultProperties(array(
            'tpl' => 'lgnActiveUser',
            'sortBy' => 'username',
            'sortDir' => 'DESC',
            'limit' => 10,
            'offset' => 0,
            'placeholderPrefix' => 'au.',
            'outputSeparator' => "\n",
            'toPlaceholder' => '',
        ));
    }

    /**
     * Run the controller and return the output
     * @return string
     */
    public function process() {
        $users = $this->getUsers();
        $list = $this->iterate($users);
        return $this->output($list);
    }

    /**
     * Get the active user objects
     * @return array
     */
    public function getUsers() {
        $placeholderPrefix = $this->getProperty('placeholderPrefix','au.');
        $classKey = $this->getProperty('classKey','modUser');
        
        $sortBy = $this->modx->getOption('sortBy',$_REQUEST,$this->getProperty('sortBy','username'));
        $sortDir = $this->modx->getOption('sortDir',$_REQUEST,$this->getProperty('sortDir','DESC'));
        $limit = $this->modx->getOption('limit',$_REQUEST,$this->getProperty('limit',10,'isset'));
        $offset = $this->modx->getOption('offset',$_REQUEST,$this->getProperty('offset',0));
        
        $c = $this->modx->newQuery($classKey);
        $c->innerJoin('modUserProfile','Profile');
        $c->innerJoin('modSession','Session','Session.id = Profile.sessionid');
        $c->where(array(
           'Profile.blocked' => false,
           $classKey.'.active' => true,
        ));
        $total = $this->modx->getCount($classKey,$c);
        $c->select($this->modx->getSelectColumns($classKey,$classKey));
        $c->select($this->modx->getSelectColumns('modUserProfile','Profile','',array('id'),true));
        if (!empty($limit)) {
            $c->limit($limit,$offset);
        }
        $c->sortby($sortBy,$sortDir);
        $users = $this->modx->getCollection($classKey,$c);
        $this->modx->setPlaceholder($placeholderPrefix.'total',$total);
        $this->modx->setPlaceholder($placeholderPrefix.'offset',$offset);
        $this->modx->setPlaceholder($placeholderPrefix.'limit',$limit);

        return $users;


    }

    /**
     * Iterate and template each user
     * 
     * @param array $users
     * @return array
     */
    public function iterate(array $users = array()) {
        $tpl = $this->getProperty('tpl','ActiveUser');
        $tplType = $this->getProperty('tplType','modChunk');

        $list = array();
        $idx = 0;
        /** @var modUser $user */
        foreach ($users as $user) {
            $userArray = $user->toArray();
            $userArray['idx'] = $idx;
            $userArray['alt'] = $idx % 2;
            $list[] = $this->login->getChunk($tpl,$userArray,$tplType);
            $idx++;
        }
        return $list;
    }

    /**
     * Output the data
     * @param array $list
     * @return string
     */
    public function output(array $list = array()) {
        $outputSeparator = $this->getProperty('outputSeparator',"\n",'isset');
        $output = implode($outputSeparator,$list);
        $toPlaceholder = $this->getProperty('toPlaceholder','','isset');
        if (!empty($toPlaceholder)) {
            $this->modx->toPlaceholder($toPlaceholder,$output);
            return '';
        }
        return $output;
    }

}
return 'LoginActiveUsersController';