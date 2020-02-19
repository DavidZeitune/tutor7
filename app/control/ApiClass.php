<?php
class ApiClass extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        
        $url = "https://jsonplaceholder.typicode.com/users/";
        //coloco no corpo o resultado
        $content = file_get_contents($url);
        //uso o encode para transformar
        print_r(json_encode($content));
        
        
                  
    }
}
