<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once "Modules/WorkspaceFolder/classes/class.ilObjWorkspaceFolderGUI.php";

/**
* Class ilObjWorkspaceRootFolderGUI
*
* @author Stefan Meyer <meyer@leifos.com>
* @version $Id: class.ilObjRootFolderGUI.php 27165 2011-01-04 13:48:35Z jluetzen $Id: class.ilObjRootFolderGUI.php,v 1.13 2006/03/10 09:22:58 akill Exp $
*
* @ilCtrl_Calls ilObjWorkspaceRootFolderGUI: 
* 
* @extends ilObject2GUI
*/
class ilObjWorkspaceRootFolderGUI extends ilObjWorkspaceFolderGUI
{
	function getType()
	{
		return "wsrt";
	}
	
	function setTabs()
	{
		parent::setTabs(false);
	}
}

?>