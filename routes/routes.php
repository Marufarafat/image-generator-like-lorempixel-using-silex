<?php


use Symfony\Component\HttpFoundation\{Request, Response};

$app->get('/{weight}/{height}', function(Request $request, Silex\Application $app, $weight, $height){

    $image = $app['db']->fetchAssoc("SELECT filename FROM images ORDER BY rand() LIMIT 1");
    
    $cacheKey = "{$image['filename']}:{$weight}:{$height}";

    $placeholder = $app['cache']->fetch($cacheKey);
    
    
    if($placeholder === false) {
        $placeholder = $app['image']
                            ->make(INC_ROOT."/img/".$image['filename'])
                            ->fit($weight, $height)
                            ->greyscale()   
                            ->response('png');
        $app['cache']->store($cacheKey, $placeholder);
    }    
    return new Response($placeholder, 200,[
        "Content-Type" => "image/png"
    ]);
})->assert('weight', "[0-9]+")->assert('height', '[0-9]+');