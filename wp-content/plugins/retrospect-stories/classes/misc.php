<?

/*
 * 
 * 
 *  
 * Misc Functions
 * 
 * 
 *
 */



function generateRandomEmail(){
	 $tlds = array("com", "net", "gov", "org", "edu", "biz", "info");
	  $char = "0123456789abcdefghijklmnopqrstuvwxyz";
	  $ulen = mt_rand(5, 10);
      $dlen = mt_rand(7, 17);
	  $a = "";
	  for ($i = 1; $i <= $ulen; $i++) {
	  	$a .= substr($char, mt_rand(0, strlen($char)), 1);
	  }
	  $a .= "@";
	  for ($i = 1; $i <= $dlen; $i++) {
      	$a .= substr($char, mt_rand(0, strlen($char)), 1);
      }
	  $a .= ".";
	  $a .= $tlds[mt_rand(0, (sizeof($tlds)-1))];	
	  return $a;
}

function generateRandomUser(){
	 $tlds = array("com", "net", "gov", "org", "edu", "biz", "info");
	  $char = "0123456789abcdefghijklmnopqrstuvwxyz";
	  $ulen = mt_rand(5, 10);
      $dlen = mt_rand(7, 17);
	  $a = "";
	  for ($i = 1; $i <= $ulen; $i++) {
	  	$a .= substr($char, mt_rand(0, strlen($char)), 1);
	  }
	  return $a;
}
