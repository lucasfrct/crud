<?php
require_once ( "autoload.php" );

use Tester as Tester;
use Connect as Connect;
use Modeldata as Modeldata;
use Crud as Crud;

#Tester::on ( "", function ( $assert ) { $assert::ok ( true, "" ); } );

Tester::on ( "Teste Unitário", function ( $assert ) {
	$assert::ok ( FALSE, "teste de unidade 1" );
	#$assert::ok ( true, "teste de unidade 2" );
} );