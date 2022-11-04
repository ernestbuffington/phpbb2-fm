<?
// Edit the following:
// Usually the mysql binary logfiles are written with an incrementing extension
// file.001
// file.002
// file.003
// ...
// Here you can adjust from what number to what number they shall be processed.
$startlog = 1;
$endlog = 30;
$binlogpath = '/var/lib/mysql/abcdefgh-bin.$$$'; 
// The number of '$' tells how long the extension is
// '$$$' (= 3 '$' in a row) will be replaced by '001', '002', '003' etc.
// '$$$$$' (= 5 '$' in a row) will be replaced by '00001', '00002', '00003' etc.

?>