<?php

/**
* Class for mysql db quering
*/
class db
{
   var $host="";
   var $user="";
   var $password="";
	var $database="";
     
	 var $iConnectId; 
	 var $iResultId; 
   var $iCurrentRow; 
	 var $iNumRows;
	 var $szSQLString; 
   var $arRow; 
   var $ArrayResult;
     
     /**********************function to be used when you have a config file ********/ 
     function SetVariables()
     {
        global $config;
     	$this->host=$config['db_server'];
        $this->user=$config['db_user'];
        $this->password=$config['db_password'];
        $this->database=$config['db_db'];
     }
     /*******************connection and intialization of members *******************/ 
     function Connect() 
     { 
         $this->SetVariables();
	 $this->iConnectId = false; 
         $this->iResultId = false;
	 $this->szSQLString = ""; 
         $this->iNumRows = false; 
         $this->arRow = false; 
         $this->iCurrentRow = 0; 

         $iRet = true; 

         $this->iConnectId = @mysql_connect( $this->host , $this->user, $this->password ); 
         if( $this->iConnectId ) 
         {
            $iRet = @mysql_select_db( $this->database, $this->iConnectId ); 
            if( !$iRet && (!defined('NOERROROUT') || NOERROROUT != 1))
               echo "<font color=red>Selected Database doesn't exist!</font>"; 
         }
         else 
         {
				if (!defined('NOERROROUT') || NOERROROUT != 1)
					echo "<font color=red>Can not connect to MYSQL Database!</font>";
            $iRet = false; 
         } 
         return $iRet; 
      }
	  /*********************** reset members*****************************************/ 
      function ReUseConn( $iDBConnection ) 
      { 
         $this->iConnectId = false; 
         $tshis->szSQLString = ""; 
         $this->iResultId = false; 
         $this->iNumRows = false; 
         $this->arRow = false; 
         $this->iCurrentRow = 0; 

         $this->iConnectId = $iDBConnection; 
      } 
	  /*********************** ther actualy query executing method *****************
	  * @return boolean
	  * @param $szSelect string
	  * @desc ruleaza query in baza de date
	  */
	  function Query( $szSelect )
      { 
	  
         $this->szSQLString = ""; 
         $this->szSQLString = $szSelect; 
         $this->iResultId = false; 
         $this->iNumRows = false; 
         $this->arRow = false; 
         $this->iCurrentRow = 0; 
		 $this->ArrayResult = array();
		 	
         $iRet = true; 

         if( $this->iConnectId != 0) 
         { 
            if( strlen( $this->szSQLString ) > 0 ) 
            { 

               $this->iResultId = mysql_query( $this->szSQLString, $this->iConnectId ); 

               if( $this->iResultId ) 
               {                  
                  if( preg_match("/^(?:[\s\n\t\r\v]*)select|show /i",$this->szSQLString)  )
				  {
                     $this->iNumRows = mysql_num_rows( $this->iResultId ); 
					 while($row = mysql_fetch_assoc( $this->iResultId ) )
					 {
					 	$this->ArrayResult[] = $row;	
					 }
					 unset($row);

				}
                  else 
                     $this->iNumRows  = mysql_affected_rows(  $this->iConnectId  ); 
               } 
               else 
               { 
                  $iRet = false;
						if (DEBUG_ON=='1') echo mysql_error().'<br>'.$this->szSQLString.'<br>';
			   } 
            } 
            else 
            { 
               echo "Open called without any SQL Query!!!"; 
               $iRet = false; 
            } 
         } 

         return $iRet; 
      } 
      /************************** numebr of records returned ************************/ 
      function NumRows() 
      { 
         if( $this->iConnectId ) 
            return $this->iNumRows; 
         else 
            return false; 
      }
      /*************************** jump to specified record *************************/ 
      function MoveTo( $iRow ) 
      { 
         if( $this->iConnectId && ($iRow <= $this->iNumRows) && ($this->iNumRows > 0) ) 
         { 
            if( mysql_data_seek( $this->iResultId , $iRow ) ) 
            {  
			   $this->iCurrentRow = $iRow; 
               return true; 
            } 
            else 
               return false; 
         } 
         else 
            return false; 
      }
      /******************** fetch the result  and get the nex record ****************/ 
      function NextRow() 
      { 
         $iRet = true; 

         if( $this->iNumRows ) 
         {  
		 if ($this->iCurrentRow<count($this->ArrayResult))
		 	$this->arRow = $this->ArrayResult[$this->iCurrentRow];
		else
			$this->arRow = false;
		
		if( $this->arRow ) 
               $this->iCurrentRow++; 
        else 
               $iRet = false; 
         } else { 
            $iRet = false; 
         } 
         return $iRet; 
      }
      /******************** return the field value **********************************/ 
      function GetField( $szFieldName ) 
      { 
	   return $this->arRow[$szFieldName]; 
      }
      /******************************************************************************/ 
      function EchoField( $szFieldName ) 
      { 
         echo $this->arRow[$szFieldName]; 
      } 
      /********************** free the mysql query result ***************************/ 
      function FreeResult() 
      { 
         @mysql_free_result($this->iResultId);
      } 
      /******************** close the connection ************************************/ 
      function Close() 
      { 
         @mysql_free_result($this->iResultId);
		 return @mysql_close($this->iConnectId);
	  } 
      /*********************************** end ******************************/        
}
?>