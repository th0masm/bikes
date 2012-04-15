<?php

class Application_Model_DbTable_Countries extends Zend_Db_Table_Abstract
{

    protected $_name = 'tblCountries';

		protected $_primary = 'idCountry';
		
		
		
		
		public function getAllCountriesForForm(){
			$rows = $this->fetchAll()->toArray();
			foreach($rows AS $row)
				$res[$row['idCountry']] = $row['strCountryName'];
			return $res;
		}
		
		
		
}

