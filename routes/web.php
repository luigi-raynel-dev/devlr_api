<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version(); 
});


$router->get('/devlr', function () use ($router) {
    return 'teste';
});

/**
 * Grupo de rotas dos artigos
 */
$router->group([], function () use ($router) {
    // Exibi todos os artigos ou os correspondentes a pesquisa
    $router->get('/articles', 'ArticleController@index');
    // Exibi um artigo
    $router->get('/articles/{id}', 'ArticleController@show');
    // Cadastra um artigo
    $router->post('/articles', 'ArticleController@store');
    // Edita um artigo
    $router->put('/articles/{id}', 'ArticleController@update');
    /// Apaga um artigo
    $router->delete('/articles/{id}', 'ArticleController@destroy');
});

/**
 * Grupo de rotas das categorias
 */
$router->group([], function () use ($router) {
    // Exibi todas as categorias
    $router->get('/categorys', 'CategoryController@index');
    // Exibi uma categoria
    $router->get('/categorys/{id}', 'CategoryController@show');
    // Cadastra uma categoria
    $router->post('/categorys', 'CategoryController@store');
    // Edita uma categoria
    $router->put('/categorys/{id}', 'CategoryController@update');
    /// Apaga uma categoria
    $router->delete('/categorys/{id}', 'CategoryController@destroy');
});
