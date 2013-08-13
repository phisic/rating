<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GlobalVar
 *
 * @author Администратор
 */
class GlobalVar {
    /**
     *
     * @var type all variables in array
     */
    private static $vars=array();
    
    /**
     * get variable in Blobalvars
     * @param type $name name of variable
     * @return type variable by name
     */
    public static function get($name, $def_val='') {
        if(isset(self::$vars[$name]))
                return self::$vars[$name];
        return $def_val;
    }
    
    /**
     * set variable value by name
     * @param type $name name of variable
     * @param type $val Value
     */
    public static function set($name, $val) {
        self::$vars[$name]=$val;
    }
    
    /**
     *
     * @param type $params array get params by key
     * @return type array or sub array or variable
     */
    public static function getAuth($params=array())
    {
        $default_params=array(
            'role'=>'',
            'field'=>'',
        );
        $params=array_merge($default_params, $params);
        if(!isset(self::$vars['authFile']))
                self::$vars['authFile']=include(Yii::app()->authManager->authFile);
        $auth_file=self::$vars['authFile'];
        if($params['role']!='' && isset($auth_file[$params['role']]))
        {
            $auth_file=$auth_file[$params['role']];
            if($params['field']!='' && isset($auth_file[$params['field']]))
                $auth_file=$auth_file[$params['field']];
        }
        return $auth_file;
    }
    
}
