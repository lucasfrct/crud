<?php
#Modeldata.php
include_once ( "autoload.php" );

use Crud as Crud;
use ICrud as interfaceCrud;

class Modeldata 
{
    private $debug = Array ( );
    private $crud = null;
    
    public $action = "";
    public $table = "";
    public $data = "";
    public $query = "";

    # addiciona novas notificaçãoes no debug
    private function addDebug ( $debug  = null ): void
    {
        if ( null != $debug ) {
            array_push ( $this->debug, $debug );
        };
    }

    # reporta uma string do debug
    public function debug ( ): string 
    {
        return json_encode ( $this->debug );
    }

    # inicia um instância da classe Modeldata e injeta a injeção Connect com um objeto MySqli 
    public function __construct ( interfaceCrud $crud = null  ) 
    { 
        $this->addDebug ( "New instance Modeldata" );
        
        if ( null !== $crud ) {
            $this->crud = $crud;
            $this->addDebug ( "New instance Crud" );
        };
    }

    public function digest ( string $json ) 
    {   
        $this->addDebug ( "Action: digest" );
        $data = json_decode ( $json, true );

        if ( isset ( $data [ "action" ] ) ) {
            $this->action = $data [ "action" ];
            $this->addDebug ( "Action: load action" );
        };

        if ( isset ( $data [ "table" ] ) ) {
            $this->table = $data [ "table" ];
            $this->addDebug ( "Action: load table" );
        };


        if ( isset ( $data [ "data" ] ) ) {  
            $this->data = $data [ "data" ];
            $this->addDebug ( "Action: load data" );
        };

        if ( isset ( $data [ "query" ] ) ) { 
            $this->query = $data [ "query" ];
            $this->addDebug ( "Action: load query" );
        };
    }

    public function parseBool ( $bool = null ) 
    {
        switch ( strtolower ( $bool ) ) {
            case "true":
                $this->addDebug ( "Parseboool : true" );
                return true;
                break;
            case "false":
                $this->addDebug ( "Parseboool : false" );
                return false;
                break;
            default:
                return $bool;
                break;
        };
    }

     public function parseJsonToFieldsAndValues ( array $data = null ): array 
    {
        $parse = array ( "fields" => array ( ) , "values" => array ( ) );

        foreach ( $data as $field => $value ) {
            if ( isset ( $parse [ "fields" ] ) ) {
                array_push ( $parse [ "fields" ], ( string ) $field );
            };
            if ( isset ( $parse [ "values" ] ) ) {
                $value = $this->parseBool ( $value );
                array_push ( $parse [ "values" ], "'".$value."'" );
            };
        };

       $parse [ "fields" ] = implode ( ",", $parse [ "fields" ] );
       $parse [ "values" ] = implode ( ",", $parse [ "values" ] );
       return $parse;
    }

    #envia dados create para o crud
    public function create ( ): bool 
    {   
        $this->addDebug ( "Action: Create" );
        $data = $this->parseJsonToFieldsAndValues ( $this->data );
        return $this->crud->create ( $this->table, $data [ "fields" ], $data [ "values" ] );
    }

    public function read ( ): array 
    {
        $this->addDebug ( "Action: Read" );
        return $this->crud->read ( $this->table, $this->query );
    }


    public function update ( ): bool
    {
        $this->addDebug ( "Action: Update" );
        return $this->crud->update ( $this->table,  );
    }



    /*
    
    public function parseJsonToItem ( $data = null ): string 
    {
        $itens = array ( );

        foreach ( $data as $field => $value ) {
            $value = $this->parseBool ( $value );
            array_push ( $itens, "{$field} = '".self::$instance->parseArrayToDatabase ( $value )."'" );
        };

        return implode ( ",", $itens );
    }
    
    /*
    public function parseArrayToDatabase ( $data = null ) 
    {
        if ( is_array ( $data ) && count ( $data ) > 0 ) {
            $data = serialize ( $data );
        };

        return $data;
    }

    public function parseDatabaseToArray ( $data = null ) 
    {

        $data = array_map ( function ( $item ) {
            if ( $item == serialize ( false ) || @unserialize ( $item ) !== false ) {
                return unserialize ( $item );
            } else {
                return $item;
            };
        }, $data );
        
        $data = array_map ( function ( $item, $index ) {
                if ( ( $data == serialize ( false ) || @unserialize ( $data ) !== false ) ) {
                    $data = unserialize ( $data );
                }
                return $item;
            }, $data
        );
        return $data;

    } 

   

    public function digest ( string $data = null ): array 
    {
        return json_decode ( $data, true );
    }

    private function __clone ( ) { }
    
    private function __wakeup ( ) { }
    */
}
/**/


$model = new Modeldata ( Crud::on ( Connect::on ( "127.0.0.1:3306", "root", "", "test" ) ) );

#$model->action;
#$model->table;
#$model->data;
$model->digest ( '{ "action": "create", "table": "test", "data": { "user": "root", "password": "1234" } }' );
$model->create ( );
$model->digest ( '{ "action": "read", "table": "test", "query": "id >= 5" }' );
$model->read ( );
#$model->
echo $model->debug ( );


#$model->parseBool ( "true" );
#echo $model->parseBool ( "parsebool" );
#$parse = $model->parseJsonToFieldsAndValues ( $json );
#echo $parse [ "fields" ];
#echo $parse [ "values" ];
#var_dump ( $model->parseJsonToItem ( $json ) );
#var_dump ( $model->digest ( $json ) );
//print_r ( json_decode ( $json ) );