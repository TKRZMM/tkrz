<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:53
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
 * ==>                                  '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                                                    AnyCreature from Outerspace
 *  -> ...                                                            AnyCreature from Outerspace
 *
 */
namespace classes\system;


use classes\core\CoreExtends;


class SystemLayout extends CoreExtends
{

	// Initialsiere Variable
	public $myDynObj;       // Objekt Handler aus dem Core - Klassen - System
	public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse










	// Klassen eigener Konstruktor
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

	}   // END function __construct(...)










	// Initial Methode - Erzeugt das Nav-Menü
	function createNavMenueFullHandling(& $navArray)
	{

		// Query für die Navigation ermitteln
		$query = $this->getQuery('getNavMenue');

		// Führe Query aus
		$result = $this->query($query);

		if ($this->num_rows($result) >= 1) {

			while ($row = $result->fetch_object()) {

				$sourceTypeID = $row->sourceTypeID;
				$sourceTypeName = $row->sourceTypeName;
				$sourceTypeFileUploadDirName = $row->fileUploadDirName;
				$iconSourceType = $row->iconTag;

				$sourceSystemID = $row->sourceSystemID;
				$sourceSystemName = $row->sourceSystemName;


				$navArray['NavMenue'][$sourceTypeID][$sourceTypeName][$sourceSystemID] = $sourceSystemName;
				$navArray['NameSourceTypeFileUploadDir'][$sourceTypeID] = $sourceTypeFileUploadDirName;
				$navArray['IconSourceType'][$sourceTypeID] = $iconSourceType;

			}

			$this->free_result($result);

			return true;
		}

		return false;

	}    // END function getNavMenueFullHandling()


}   // END class SystemLayout extends CoreExtends