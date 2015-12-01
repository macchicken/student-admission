<?php 
function connectdb(){
  $link=pg_connect("host=db.doc.ic.ac.uk port=5432 user=g0836218_u password=8mhUZlUtsa dbname=g0836218_u");
   if( ! $link )
{ 
	die("couldn't connect to postgres");
}
return($link);
}?>