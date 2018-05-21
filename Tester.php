<?php
#Tester.php 

header ( 'Content-Type: text/html; charset=utf-8' );
error_reporting ( E_ALL ^ E_NOTICE );
error_reporting ( E_ALL );
ini_set ( 'error_reporting', E_ALL );
ini_set ( 'display_startup_errors', TRUE );
ini_set ( "display_errors", TRUE );
ini_set ( "default_charset", TRUE );

echo '<style>
body {
    font-family: verdana, sans-serif;
}
.container { 
    border: solid 1px #9E9E9E;
    display block; margin: 1px 0; 
    padding: 7px 14px; 
    min-height: 60px; 
    background-color: #EEEEEE;
    font-size: 14px;
}

.container h5 {
    margin: 0;
    padding: 7px 0;
    font-size  1em;
}
.container small, .container section {
    font-size: 0.9em;
    display: block;
    background-color: #DDD;
    padding: 7px;
}

.container small {
    margin: 14px 0 0 0;
}
.container small sub {
    font-size: 0.8em;
}
.container small em {
    float: right;
    right: 0;
}
.container section {
    background-color: #BBB;
}
</style>';

class Tester 
{
    private static $instance = NULL;

    private $init = 0;
    private $tap = Array ( );
    private $middle = 0;
    private $total = 0;

    private $offset = 0.000007 ;
    private $success = array ( "#0A0", "rgba(0,190,0,0.1)" );
    private $error = array ( "#C00", "rgba(190,0,0,0.1)" );

    private $status = FALSE;
    private $repeat = 1;

    private $unity = Array ( );

    private $outResponse = [ "true", "false", "undefined" ];

    private function timeInit ( ) 
    {
        return self::$instance->init = microtime ( 1 );
    }

    private function timeTap ( ) 
    {
        $tap = ( microtime ( 1 ) -  self::$instance->init );
        array_push ( self::$instance->tap, $tap );

        return $tap;
    }

    private function timeMiddle ( ) 
    {
        $middle = array_reduce ( self::$instance->tap, function ( $previous, $item ) {
            return $previous += $item;
        } );

        return self::$instance->middle = ( $middle / count ( self::$instance->tap ) );  
    }

    private function timeTotal ( ) 
    {   
        self::$instance->timeTap ( );
        return self::$instance->total =  array_reduce ( self::$instance->tap, function ( $previous, $item ) {
            return $previous += $item;
        } );
    }

    private function unity ( bool $status = FALSE, string $title = "Test Undefined!", int $repeat = 1 ) 
    {
        self::$instance->unity = array ( 
            "title"=> $title, 
            "repeat"=> $repeat,
            "status"=> $status, 
            "tester"=> array ( )
        );

        /*array_push ( self::$instance->unity [ "tester" ], array ( "status"=> FALSE, "description"=> $description, "timeMiddle"=> 0, "timeTotal"=> 0, "repeat"=> 1 ) ); */ 

        return self::$instance->unity;
    }

    private function assert ( ) 
    {
        return self::$instance;
    }

    private function inner ( array $unity ) 
    {    
        $set = ( $unity [ "status" ] !== FALSE ) ? self::$instance->success : self::$instance->error;

        echo '<div class="container" style="border-color: '.$set [ 0 ].'">
            <h5>'.$unity [ "title" ].'</h5>
            <small>
                <span>'.round ( $tester [ "timeMiddle" ], 6 ).'ms</span> 
                <sub>(x'.$tester [ "repeat" ].')</sub>
                <em>Tempo total: '.round ( ( $tester [ "timeTotal" ] / 1000 ), 2 ).'s</em>
            </small>';

        foreach ( $unity [ "tester" ] as $tester ) {
            echo '<section style="background-color:  '.$set [ 1 ].'">'.$tester [ "description" ].'</section>';
        };

        echo '</div>';
    }

     public static function ok ( bool $status = FALSE, string $description = NULL ): bool
    { 
        self::$instance->status = ( $status === TRUE && self::$instance->status === TRUE ) ? TRUE : FALSE; 
        
        array_push ( self::$instance->unity [ "tester" ], array ( 
            "status"=> $status, 
            "description"=> $description,
            "timeTotal"=> ( self::$instance->init - self::$instance->timeTap ( ) ),
        ) );

        return $status;
    }

    public static function on ( string $title = "", Closure $fn = NULL, int $repeat = 1 ) 
    {
        if ( self::$instance === NULL ) {
            self::$instance = new self;
        };

        if ( empty ( $title ) && $fn === NULL ) {
            self::$instance->inner ( self::$instance->unity ( ) );
        };

        if ( !empty ( $title ) && is_string ( $title ) && $fn instanceof Closure && is_numeric ( $repeat ) ) {

            self::$instance->timeInit ( );

            self::$instance->status = TRUE;
                
            self::$instance->unity ( self::$instance->status,  $title, $repeat );
            
            for ( $i = 0; $i < $repeat; $i++ ) {
                $fn ( self::$instance );
                self::$instance->timeTap ( );
            };

            self::$instance->timeTotal ( );

            self::$instance->inner ( self::$instance->unity );
        };



    }

    public function ona ( string $title = NULL, Closure $fn = NULL, int $repeat = 1 ) 
    {
        
        /*self::reset ( );

        if ( is_string ( $title ) && $fn instanceof Closure && is_numeric ( self::$repeat ) ) {

            self::$title = $title;
            self::$repeat = $repeat;
            
            $timeOfFunctions = array ( );
            $init = microtime ( 1 );
            
            for ( $i = 0; $i < self::$repeat; $i++ ) {
                $time = microtime ( 1 );
                $fn ( self::assert ( ) );
                array_push ( $timeOfFunctions, ( ( microtime ( 1 ) - $time ) * 1000 ) );
            };

            $timeOfExec = ( ( ( microtime ( 1 ) - $init ) - self::$offset ) * 1000 );
            $timeOfEachExec = ( $timeOfExec / self::$repeat );

            $totalOfFunctions = self::sum ( $timeOfFunctions );
            $timeOfEachFunction = ( $totalOfFunctions / count ( $timeOfFunctions ) );
            
            self::$timeOfTest = ( ( $timeOfExec + $totalOfFunctions ) / 2 );
            self::$timeOfEachTest = ( ( $timeOfEachExec + $timeOfEachFunction ) / 2 );

        } else {
            self::$title = ( empty ( self::$title ) ) ? "Error!" : self::$title;
            self::$description = " Erro de Sintaxe. Favor verificar os argumentos de entrada do teste.";
        };

        self::inner ( );
        */
    }
};