--TEST--
oci_lob_write() and friends
--SKIPIF--
<?php if (!extension_loaded('oci8')) die("skip no oci8 extension"); ?>
--FILE--
<?php
	
require dirname(__FILE__).'/connect.inc';
require dirname(__FILE__).'/create_table.inc';

$ora_sql = "INSERT INTO
                       ".$schema.$table_name." (id, clob)
                      VALUES (2, empty_clob())
                      RETURNING
                               clob
                      INTO :v_clob ";

$statement = oci_parse($c,$ora_sql);
$clob = oci_new_descriptor($c,OCI_D_LOB);
oci_bind_by_name($statement,":v_clob", $clob,-1,OCI_B_CLOB);
oci_execute($statement, OCI_DEFAULT);

oci_commit($c);  // This will cause subsequent ->write() to fail
$clob->write("data");

require dirname(__FILE__).'/drop_table.inc';

echo "Done\n";

?>
--EXPECTF--
Warning: OCI-Lob::write(): ORA-22990: %s in %s on line 19
Done
