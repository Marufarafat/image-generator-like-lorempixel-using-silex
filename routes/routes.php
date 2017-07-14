<?php


use Symfony\Component\HttpFoundation\{Request, Response};

//http://localhost/loremimage/500/300?image=3
$app->get('/{weight}/{height}', function(Request $request, Silex\Application $app, $weight, $height){

    $cluse = $request->get("image") ? "WHERE id = ?" : "ORDER BY rand() LIMIT 1";

    $image = $app['db']->fetchAssoc("SELECT filename FROM images {$cluse}", [$request->get("image")]);
    
    $cacheKey = "{$image['filename']}:{$weight}:{$height}";

    $placeholder = $app['cache']->fetch($cacheKey);
    
    
    if($placeholder === false) {
        $placeholder = $app['image']
                            ->make(INC_ROOT."/img/".$image['filename'])
                            ->fit($weight, $height)
                            ->greyscale()   
                            ->response('png');
//        $app['cache']->store($cacheKey, $placeholder);
    }    
    return new Response($placeholder, 200,[
        "Content-Type" => "image/png"
    ]);
})->assert('weight', "[0-9]+")->assert('height', '[0-9]+');