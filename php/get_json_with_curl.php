<?php

    /*
        $url
            url do endpoint
        $args
            token
                contem o token/key para ser utilizada com autenticacao bearer
            authentication_type
                contem o tipo de autenticacao
    */

    function get_json_with_curl( $url, $args = false ){

        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(
            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,
        );

        $ch = curl_init( $url );
        curl_setopt_array( $ch, $options );

        // PARAMETROS ADICIONAIS ================================================================
        if(isset($args['token'])){
            
            if(isset($args['authentication_type'])){
                if($args['authentication_type'] == 'bearer'){
                    
                    $headers = array(
                        "Accept: application/json",
                        "Authorization: Bearer " . $args['token'],
                    );
        
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
            }
        }
        // ======================================================================================

        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        
        $json = json_decode($header['content']);
        
        return $json;
    }