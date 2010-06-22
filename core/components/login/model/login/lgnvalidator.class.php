<?php
/**
 * Login
 *
 * Copyright 2010 by Jason Coward <jason@modxcms.com> and Shaun McCormick
 * <shaun@modxcms.com>
 *
 * Login is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
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
 * Handles custom validaton
 *
 * @package login
 */
class lgnValidator {
    /**
     * @var array $errors A collection of all the processed errors so far.
     * @access public
     */
    public $errors = array();
    /**
     * @var array $fields A collection of all the validated fields so far.
     * @access public
     */
    public $fields = array();
    /**
     * @var modX $modx A reference to the modX instance.
     * @access public
     */
    public $modx = null;
    /**
     * @var Login $login A reference to the Login instance.
     * @access public
     */
    public $login = null;

    /**
     * The constructor for the lgnValidator class
     *
     * @param Login &$login A reference to the Login class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @return lgnValidator
     */
    function __construct(Login &$login,array $config = array()) {
        $this->login =& $login;
        $this->modx =& $login->modx;
        $this->config = array_merge(array(
        ),$config);
    }

    /**
     * Validates an array of fields. Returns the field names and values, with
     * the field names stripped of their validators.
     *
     * The key names can be in this format:
     *
     * name:validator=param:anotherValidator:oneMoreValidator=`param`
     *
     * @access public
     * @param array $keys The fields to validate.
     * @return array An array of field name => value pairs.
     */
    public function validateFields(array $keys = array()) {
        $this->fields = array();
        foreach ($keys as $k => $v) {
            $key = explode(':',$k);
            $validators = count($key);
            if ($validators > 1) {
                $this->fields[$key[0]] = $v;
                for ($i=1;$i<$validators;$i++) {
                    $this->validate($key[0],$v,$key[$i]);
                }
            } else {
                $this->fields[$k] = $v;
            }
        }
        return $this->fields;
    }

    /**
     * Validates a field based on a custom rule, if specified
     *
     * @access public
     * @param string $key The key of the field
     * @param mixed $value The value of the field
     * @param string $type Optional. The type of the validator to apply. Can
     * either be a method name of lgnValidator or a Snippet name.
     * @return boolean True if validation was successful. If not, will store
     * error messages to $this->errors.
     */
    public function validate($key,$value,$type = '') {
        $validated = false;

        $hasParams = strpos($type,'=');
        $param = null;
        if ($hasParams !== false) {
            $param = str_replace('`','',substr($type,$hasParams+1,strlen($type)));
            $type = substr($type,0,$hasParams);
        }

        if (method_exists($this,$type) && $type != 'validate') {
            /* built-in validator */
            $validated = $this->$type($key,$value,$param);

        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $type))) {
            /* custom snippet validator */
            $validated = $snippet->process(array(
                'key' => $key,
                'value' => $value,
                'param' => $param,
                'type' => $type,
                'validator' => &$this,
                'errors' => &$this->errors,
            ));

        } else {
            /* no validator found */
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Register] Could not find validator "'.$type.'" for field "'.$key.'".');
            $validated = true;
        }

        if (is_array($validated) && !empty($validated)) {
            $this->errors = array_merge($this->errors,$validated);
            $validated = false;
        } else if ($validated != true) {
            $this->errors[$key] .= ' '.$validated;
            $validated = false;
        }
        return $validated;
    }

    /**
     * Checks to see if field is required.
     */
    public function required($key,$value) {
        return !empty($value) ? true : $this->modx->lexicon('register.field_required');
    }

    /**
     * Checks to see if field is blank.
     */
    public function blank($key,$value) {
        return empty($value) ? true : $this->modx->lexicon('register.field_not_empty');
    }

    /**
     * Checks to see if passwords match.
     */
    public function password_confirm($key,$value,$param = 'password_confirm') {
        if (empty($value)) return $this->modx->lexicon('register.password_not_confirmed');
        if ($this->fields[$param] != $value) {
            return $this->modx->lexicon('register.password_dont_match');
        }
        return true;
    }

    /**
     * Checks to see if field value is an actual email address.
     */
    public function email($key,$value) {
        /* validate length and @ */
        if (!@ereg("^[^@]{1,64}\@[^\@]{1,255}$", $value)) {
            return $this->modx->lexicon('register.email_invalid');
        }

        $email_array = explode("@", $value);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!@ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i])) {
                return $this->modx->lexicon('register.email_invalid');
            }
        }
        /* validate domain */
        if (!@ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return $this->modx->lexicon('register.email_invalid_domain');
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!@ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i])) {
                    return $this->modx->lexicon('register.email_invalid_domain');
                }
            }
        }
        return true;
    }

    /**
     * Checks to see if field value is shorter than $param
     */
    public function minLength($key,$value,$param = 0) {
        if (strlen($value) < $param) {
            return $this->modx->lexicon('register.min_length',array('length' => $param));
        }
        return true;
    }

    /**
     * Checks to see if field value is longer than $param
     */
    public function maxLength($key,$value,$param = 999) {
        if (strlen($value) > $param) {
            return $this->modx->lexicon('register.max_length',array('length' => $param));
        }
        return true;
    }

    /**
     * Checks to see if field value is less than $param
     */
    public function minValue($key,$value,$param = 0) {
        if ((float)$value < (float)$param) {
            return $this->modx->lexicon('register.min_value',array('value' => $param));
        }
        return true;
    }

    /**
     * Checks to see if field value is greater than $param
     */
    public function maxValue($key,$value,$param = 0) {
        if ((float)$value > (float)$param) {
            return $this->modx->lexicon('register.max_value',array('value' => $param));
        }
        return true;
    }

}