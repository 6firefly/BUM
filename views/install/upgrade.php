<?php
/**
 * Install controller main view file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License
 *  
 */

/* @var $this InstallController */

$this->breadcrumbs=array(
	'Install',
);

$this->menu=array(    
	array('label'=>'Install with MySQL', 'url'=>array('install/MySQL'), 'visible'=>($this->module->install)),
	array('label'=>'Install with PostgreSQL', 'url'=>array('install/PostgreSQL'), 'visible'=>($this->module->install)),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>($this->module->install)), // separator
    
	array('label'=>'Usefull things (How to?)', 'url'=>array('install/howTo'), 'visible'=>($this->module->install)),
);

?><UL>
    <LI <?php echo isset($_GET['v2_to_v202'])?"":"style='display:none;'"; ?>>update_from_v2_to_v2.02.sql <?php
    $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2_to_v2.02.sql');
    ?><PRE class="box"><?php
        echo $file;
    ?></PRE></LI>
    <LI <?php echo isset($_GET['v202_to_v203'])?"":"style='display:none;'"; ?>>
        <UL>
            <LI>MySQL:<BR/>
                update_from_v2.02_to_v2.03.MySQL.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2.02_to_v2.03.MySQL.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
            <LI>PostgreSQL:<BR/>
                update_from_v2.02_to_v2.03.postgre.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2.02_to_v2.03.postgre.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
        </UL>
    </LI>
    <LI <?php echo isset($_GET['v203_to_v204'])?"":"style='display:none;'"; ?>>
        <UL>
            <LI>MySQL:<BR/>
                update_from_v2.03_to_v2.04.MySQL.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2.03_to_v2.04.MySQL.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
            <LI>PostgreSQL:<BR/>
                update_from_v2.03_to_v2.04.postgre.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2.03_to_v2.04.postgre.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
        </UL>
    </LI>
    <LI <?php echo isset($_GET['v2_to_v3'])?"":"style='display:none;'"; ?>>
        <UL>
            <LI>MySQL:<BR/>
                update_from_v2_to_v3.MySQL.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2_to_v3.MySQL.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
            <LI>PostgreSQL:<BR/>
                update_from_v2_to_v3.postgre.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v2_to_v3.postgre.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
        </UL>
    </LI>
    <LI <?php echo isset($_GET['v3_to_v301'])?"":"style='display:none;'"; ?>>
        <UL>
            <LI>MySQL:<BR/>
                update_from_v3_to_v301.MySQL.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v3_to_v301.MySQL.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
            <LI>PostgreSQL:<BR/>
                update_from_v3_to_v301.postgre.sql <?php
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'update_from_v3_to_v301.postgre.sql');
            ?><PRE class="box"><?php
                echo $file;
            ?></PRE></LI>
        </UL>
    </LI>
</UL><?php
