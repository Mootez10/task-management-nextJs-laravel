<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
         

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>API Specifications</title> 
    </head>
    <body>
        <div id="swagger-api"></div>
        @vite('resources/js/swagger.js')
    </body>

  
    
</html>
