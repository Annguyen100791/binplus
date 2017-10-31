<?php
//$db = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.59.37.99)(PORT = 1521))) (CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = CSS215)))"; 
$db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.59.0.3)(PORT = 1521))
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.59.0.4)(PORT = 1521))
      (LOAD_BALANCE = on)
      (FAILOVER = on)
    )
    (CONNECT_DATA =
      (SERVICE_NAME = cssdng)
      (FAILOVER_MODE =
        (TYPE = session)
        (METHOD = basic)
      )
    )
  )
"; 
$conn = oci_connect('css', 'css489everdie', $db); 
if(!$conn){ 
die('Failed');
}else
	//die('OK');
	$stid = oci_parse($conn, 'SELECT * FROM khachhang_einv');
	oci_execute($stid);
	//var_dump($stid);
	echo "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
?>