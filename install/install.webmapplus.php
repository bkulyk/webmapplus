<?php
    function com_install(){
    $db	=& JFactory::getDBO();
		$ver = getVersion();
		$error = false;
		$sqlDir = JPATH_ADMINISTRATOR.DS."components".DS."com_webmapplus".DS."install".DS;
		
		$sql = "";

		if($ver == false):
			echo "Installing clean database...";
			$sql .= file_get_contents($sqlDir."install.sql");
		elseif($ver[1]==5 || ($ver[1]==2 && $ver[2]==5)):
			echo "N/A.<br />";
			return true;//Short circuit because we are already at latest version
		else:
			echo "Upgrade Database from version ".implode('.', $ver)."...";
			$sqlDir .= "upgrade".DS;
			switch($ver[1]){
				case 0 :	$sql .= file_get_contents($sqlDir."1.0-1.1.0-upgrade.sql"); 
	
				case 1 :	if($ver[2] == 0 || $ver[1] == 0 ):
								$sql .= file_get_contents($sqlDir."1.1-1.1.3-upgrade.sql");
                $sql .= file_get_contents($sqlDir."1.1-1.2.0-upgrade.sql");	
							endif; 
				case 2 :	if($ver[2] >= 5){
                }
                elseif($ver[2] != 4 && $ver[2] >= 1){
                  $sql .= file_get_contents($sqlDir."1.2.1-1.2.4-upgrade.sql");
                }
                elseif($ver[2] == 0){
                  $sql .= file_get_contents($sqlDir."1.2.0-1.2.1-upgrade.sql");
                  $sql .= file_get_contents($sqlDir."1.2.1-1.2.4-upgrade.sql");  
                }

							break;
			}
			
			$sql .= file_get_contents($sqlDir."1.2.4-1.2.5-upgrade.sql");
					
		endif;
		
		$sql = preg_replace('#/\*(.*?)\*/#sm', '', $sql);
		$sql = $db->splitSql($sql);
		
		foreach($sql as $stmt){
			$stmt = trim($stmt);
			if($stmt != "" && $stmt[0] != "#" && (substr($stmt,0,2) != "--")){		
				$db->setQuery( $stmt );
				$result = $db->query();
				if(!$result){
					echo "<br />".JText::_("Install Error").": ".$db->ErrorMsg()."<br />";
					$error = true;
				}
			}
		}		
		
		echo "Done!<br /> Executed ".count($sql)." SQL statements. <br />";
		return !$error;
    }
	
	
	/* Returns the version of the component based on database metadata
	 * @return bool|array - false if not installed, array if installed [0] - version, [1] - major, [2] - minor
	 */
	function getVersion(){
		$db	=& JFactory::getDBO();
		
		$sql = "SHOW COLUMNS FROM #__webmapplus_locations";
		$db->setQuery( $sql );
		$result = $db->query();
		
		if($result === false){
			return false;
		}
		
		$ver = array();
		$ver[0] = 1;
		
		$sql = "SHOW COLUMNS FROM #__webmapplus_categories;";		
		$db->setQuery( $sql );
		$result = $db->query();
		 								
		if($result != false){		
		  $ver[1] = 2;
		  
		  $sql = "SHOW COLUMNS FROM #__webmapplus_locations WHERE field = \"pano\";";		
		  $db->setQuery( $sql );
		  $result = $db->query();
		  
		  if($result != false && mysql_num_rows($result) > 0){
  			$ver[2] = 5;
  			return $ver;
		  }
		  
		  $sql = "SELECT * FROM #__webmapplus_country WHERE iso = 'RS'";
		  $db->setQuery( $sql );
			$result = $db->query();
			
		  if($result != false && mysql_num_rows($result) > 0){
  			$ver[2] = 4;
  			return $ver;
		  }
		
			$sql = "SHOW COLUMNS FROM #__webmapplus_locations  WHERE field = \"zip\"";		
			$db->setQuery( $sql );
			$db->query();
			$result = $db->loadObjectList();
			$result = $result[0];
		
			if($result->Type == "char(10)"){
				$ver[2] = 1;
			}
			else{
				$ver[2] = 0;
			}
			
			return $ver;
		}
		
		$sql = "SHOW COLUMNS FROM #__webmapplus_locations  WHERE field = \"zip\"";		
		$db->setQuery( $sql );
		$db->query();
		$result = $db->loadObjectList();
		$result = $result[0];
		
		if($result->Type == "char(10)"){
			$ver[1] = 1;
			$ver[2] = 3;
			return $ver;
		}
		
		$sql = "SHOW COLUMNS FROM #__webmapplus_locations WHERE field = \"author\";";		
		$db->setQuery( $sql );
		$result = $db->query();
		
		if($result != false && mysql_num_rows($result) > 0){
			$ver[1] = 1;
			$ver[2] = 0;
			return $ver;
		}
		
		$ver[1] = 0;
		$ver[2] = 0;
		return $ver;
	}
	
	function parseSQL($sql){
		$sql = $db->splitSql($sql);
		foreach($sql as &$s){
			
			$s = trim($s);
			if((substr($s, 0, 2) == "/*")||(substr($s, 0, 2) == "*/")||
			   (substr($s, 0, 1) == "*")||$s == ""){
				unset($s);
			}
		}
		return $sql;
	}
	
?>