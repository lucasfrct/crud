<?php
require_once ( "autoload.php" );

use Tester as Tester;
use Connect as Connect;
use Modeldata as Modeldata;
use Crud as Crud;

#Tester::on ( "title", function ( $assert ) { $assert::ok ( TRUE, "decription" ); } );
#Tester::on ( "title", function ( $assert ) { $assert::equals ( $object1, $object2, "description" ); } );

Tester::on ( "Connect::on ( );", function ( $assert ) {
	$assert::ok ( Connect::on ( ) !== NULL , "Testa	 se existe alguma intância válida para a classe Connect::on ( );" );
} );