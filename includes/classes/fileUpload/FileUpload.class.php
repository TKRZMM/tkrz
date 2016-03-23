<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 23.03.2016
 * Time: 16:49
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 * ==>                                  '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
namespace fileUpload;


use classes\core\CoreExtends;


class FileUpload extends CoreExtends
{

	// Initialsiere Variable
	public $myDynObj;       // Objekt Handler aus dem Core - Klassen - System
	public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse


	// Klassen eigener Konstruktor
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

		// Initial Aufruf für generelle Überprüfungen (Login usw.)
		// $this->loadOnInit();

	}   // END function __construct(...)




	function callMe()
	{
		$this->addMessage('FileUpload Headline', 'Hier die Message', 'info', 'FileUpload', 'Kannst nix besser machen');
	}

}   // END class FileUpload