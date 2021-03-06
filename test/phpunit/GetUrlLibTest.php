<?php
/* Copyright (C) 2010-2012	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012		Regis Houssin		<regis.houssin@capnetworks.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * or see http://www.gnu.org/
 */

/**
 *      \file       test/phpunit/GetUrlLibTest.php
 *		\ingroup    test
 *      \brief      PHPUnit test
 *		\remarks	To run this script as CLI:  phpunit filename.php
 */

global $conf,$user,$langs,$db;
//define('TEST_DB_FORCE_TYPE','mysql');	// This is to force using mysql driver
//require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__).'/../../htdocs/master.inc.php';
require_once dirname(__FILE__).'/../../htdocs/core/lib/geturl.lib.php';

if (empty($user->id))
{
	print "Load permissions for admin user nb 1\n";
	$user->fetch(1);
	$user->getrights();
}
$conf->global->MAIN_DISABLE_ALL_MAILS=1;


/**
 * Class for PHPUnit tests
 *
 * @backupGlobals disabled
 * @backupStaticAttributes enabled
 * @remarks	backupGlobals must be disabled to have db,conf,user and lang not erased.
 */
class GetUrlLibTest extends PHPUnit_Framework_TestCase
{
	protected $savconf;
	protected $savuser;
	protected $savlangs;
	protected $savdb;

	/**
	 * Constructor
	 * We save global variables into local variables
	 *
	 * @return FilesLibTest
	 */
	function __construct()
	{
		//$this->sharedFixture
		global $conf,$user,$langs,$db;
		$this->savconf=$conf;
		$this->savuser=$user;
		$this->savlangs=$langs;
		$this->savdb=$db;

		print __METHOD__." db->type=".$db->type." user->id=".$user->id;
		//print " - db ".$db->db;
		print "\n";
	}

	// Static methods
  	public static function setUpBeforeClass()
    {
    	global $conf,$user,$langs,$db;
		$db->begin();	// This is to have all actions inside a transaction even if test launched without suite.

    	print __METHOD__."\n";
    }

    // tear down after class
    public static function tearDownAfterClass()
    {
    	global $conf,$user,$langs,$db;
		$db->rollback();

		print __METHOD__."\n";
    }

	/**
	 * Init phpunit tests
	 *
	 * @return	void
	 */
    protected function setUp()
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		print __METHOD__."\n";
    }
	/**
	 * End phpunit tests
	 *
	 * @return	void
	 */
    protected function tearDown()
    {
    	print __METHOD__."\n";
    }


	/**
     * testGetRootURLFromURL
     *
     * @return	int
     */
    public function testGetRootURLFromURL()
    {
    	global $conf,$user,$langs,$db;
		$conf=$this->savconf;
		$user=$this->savuser;
		$langs=$this->savlangs;
		$db=$this->savdb;

		$result=getRootURLFromURL('http://www.dolimed.com/screenshots/afile');
    	print __METHOD__." result=".$result."\n";
		$this->assertEquals('http://www.dolimed.com',$result,'Test 1');

		$result=getRootURLFromURL('https://www.dolimed.com/screenshots/afile');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('https://www.dolimed.com',$result,'Test 2');

		$result=getRootURLFromURL('http://www.dolimed.com/screenshots');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('http://www.dolimed.com',$result);

		$result=getRootURLFromURL('https://www.dolimed.com/screenshots');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('https://www.dolimed.com',$result);

		$result=getRootURLFromURL('http://www.dolimed.com/');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('http://www.dolimed.com',$result);

		$result=getRootURLFromURL('https://www.dolimed.com/');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('https://www.dolimed.com',$result);

		$result=getRootURLFromURL('http://www.dolimed.com');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('http://www.dolimed.com',$result);

		$result=getRootURLFromURL('https://www.dolimed.com');
		print __METHOD__." result=".$result."\n";
		$this->assertEquals('https://www.dolimed.com',$result);

		return 1;
    }


    /**
     * testRemoveHtmlComment
     *
     * @return	int
     */
    public function testRemoveHtmlComment()
    {
    	global $conf,$user,$langs,$db;
    	$conf=$this->savconf;
    	$user=$this->savuser;
    	$langs=$this->savlangs;
    	$db=$this->savdb;

    	$result=removeHtmlComment('abc<!--[if lt IE 8]>aaaa<![endif]-->def');
    	print __METHOD__." result=".$result."\n";
    	$this->assertEquals('abcdef',$result,'Test 1');

    	$result=removeHtmlComment('abc<!--[if lt IE 8]>aa-->bb<!--aa<![endif]-->def');
    	print __METHOD__." result=".$result."\n";
    	$this->assertEquals('abcbbdef',$result,'Test 1');

    	return 1;
    }
}
