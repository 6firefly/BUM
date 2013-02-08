<?php
/**
 * BumDefaultInstallData class file.
 * Basin User Management, default data required for modul instalation.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * BumDefaultInstallData class.
 * @package		bum
 */

class BumDefaultInstallData {
   const DEFAULT_ROLE_SUPER_ADMIN = 'SuperAdmin';
   
   public static function getDefalutSuperAdminUserData() {
        $defaultSuperAdminUser = array(
            'user_name' => 'admin',
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'admin@noEmail.com',
            'description' => 'The default SuperAdmin user!',
        );
        
        return $defaultSuperAdminUser;
   }
    
   public static function getDefalutDemoUserData() {
        $defaultDemoUser = array(
            'user_name' => 'demo',
            'name' => 'Demo',
            'surname' => 'Demo',
            'email' => 'demo@noEmail.com',
            'description' => 'The default regular user!',
        );
        
        return $defaultDemoUser;
   }
    
}