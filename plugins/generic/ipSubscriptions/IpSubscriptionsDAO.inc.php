<?php

/**
 * @file IpSubscriptionsDAO.inc.php
 *
 * Copyright (c) 2006-2007 Gunther Eysenbach, Juan Pablo Alperin, MJ Suhonos
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.IpSubscription
 * @class IpSubscriptionsDAO
 *
 * Class for Querying CRKN IP database DAO.
 *
 */

import('db.DAO');

class IpSubscriptionsDAO extends DAO {

	/**
	 * Constructor.
	 */
	function IpSubscriptionsDAO() {
		parent::DAO();
	}

	/*
	 * Check to see if the IP in question matches a range we have for CRKN
	 */
	 function isCRKNSubscriber($ip) {

             $integer_ip = (substr($ip, 0, 3) > 127) ? ((ip2long($ip) & 0x7FFFFFFF) + 0x80000000) : ip2long($ip);

             $result = &$this->retrieve(
                     'SELECT ip FROM crkn_ips WHERE start <= ? AND ? <= end',
                     array($integer_ip, $integer_ip) 
             );

             $returner = null;
             if ($result->RecordCount() != 0) {
                 return true;
             }
                
             $result->Close();
             return false;

	 }
}

?>
