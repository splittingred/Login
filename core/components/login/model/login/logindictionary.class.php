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
 * Abstracts storage of values of posted fields and fields set by hooks.
 *
 * @package login
 */
class LoginDictionary {
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;
    /**
     * A reference to the Login instance
     * @var Login $login
     */
    public $login;
    /**
     * A configuration array
     * @var array $config
     */
    public $config = array();
    /**
     * An array of key->name pairs storing the fields passed
     * @var array $fields
     */
    public $fields = array();

    /**
     * @param Login $login
     * @param array $config
     */
    function __construct(Login &$login,array $config = array()) {
        $this->modx =& $login->modx;
        $this->formit =& $login;
        $this->config = array_merge($this->config,$config);
    }

    /**
     * Get the fields from POST
     *
     * @param array $fields A default set of fields to load
     * @return void
     */
    public function gather(array $fields = array()) {
        if (empty($fields)) $fields = array();
        $this->fields = array_merge($fields,$_POST);
        if (!empty($_FILES)) { $this->fields = array_merge($this->fields,$_FILES); }
    }

    /**
     * Set a value
     * @param string $field
     * @param mixed $value
     * @return void
     */
    public function set($field,$value) {
        $this->fields[$field] = $value;
    }

    /**
     * Get a field value
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    public function get($field,$default = null) {
        return isset($this->fields[$field]) ? $this->fields[$field] : $default;
    }

    /**
     * Return all field values in an array of key->name pairs
     * @return array
     */
    public function toArray() {
        return $this->fields;
    }

    /**
     * Set a variable number of fields by passing in a key->name pair array
     * @param array $array
     * @return void
     */
    public function fromArray(array $array) {
        foreach ($array as $k => $v) {
            $this->fields[$k] = $v;
        }
    }

    /**
     * Remove a field from the stack
     * @param string $key
     * @return void
     */
    public function remove($key) {
        unset($this->fields[$key]);
    }
    
    /**
     * @return void
     */
    public function reset() {
        $this->fields = array();
    }
}