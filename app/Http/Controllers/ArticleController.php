<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use \Cocur\Slugify\Slugify;

class ArticleController extends Controller
{
    /**
     * Retorna todos os artigos ou apenas os pesquisados
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
      // Verifica se há o parametro de pesquisa
      $search = $request->query("q");

      // Caso exista o parametro de pesquisa então...
      if($search){ 
        // Executamos a query de pesquisa com like no Eloquent
        $articles = Article::select('articles.*','categorys.name','categorys.slug AS slug_category')
        ->join('categorys', 'articles.category_id', '=', 'categorys.id')
        ->where('title','like','%'.$search.'%')
        ->orWhere('content','like','%'.$search.'%')
        ->get();
      }else{
        // Caso não exista retornamos todos os dados pelo Eloquent
        $articles = Article::select('articles.*','categorys.name','categorys.slug AS slug_category')
        ->join('categorys', 'articles.category_id', '=', 'categorys.id')
        ->get();
      }

      // Retorna os artigos
      return response()->json($articles, 200);
    }

    /**
     * Método responsável por retornar um artigo no DB
     *
     * @param Request $request
     * @param int $id
     * 
     * @return Response
     */
    public function show(int $id, Request $request)
    {
      // Busca o artigo pelo id
      $article = Article::find($id);

      // Se o artigo não existir retornamos o status 400
      if(empty($article)) 
      return response()->json(['status' => 'not found'], 400);

      // Retorna o artigo
      return response()->json($article, 200);
    }

    /**
     * Método responsável por cadastrar o artigo no DB
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

      $imageName = '';

      // Image Upload
      if($request->hasFile('image') && $request->file('image')->isValid()) {

        $requestImage = $request->image;

        $extension = $requestImage->extension();

        $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

        $requestImage->move(public_path('img/articles'), $imageName);
      }

      // Instancia a classe geradora de slug
      $slugify = new Slugify();
      // Gera o slug do título do artigo
      $slug = $slugify->slugify($request->title);

      // Instancia a entidade criada pelo Eloquent
      $article = new Article;
 
      // Define os atributos da instância
      $article->title = $request->title;
      $article->slug = $slug;
      $article->image = $imageName;
      $article->content = $request->content;
      $article->category_id = $request->category_id;

      // Invoca o método de inserção da instancia no DB pelo Eloquent
      $article->save();

      // Retorna o status de sucesso do cadastro
      return response()->json(['status' => 'created'], 201);
    }

    /**
     * Método responsável por editar um artigo no DB
     *
     * @param Request $request
     * @param int $id
     * 
     * @return Response
     */
    public function update(int $id, Request $request)
    {
      // Busca o artigo pelo id
      $article = Article::find($id);

      // Se o artigo não existir retornamos o status 400
      if(empty($article)) 
      return response()->json(['status' => 'not found'], 400);

      // Instancia a classe geradora de slug
      $slugify = new Slugify();
      // Gera o slug do título do artigo
      $slug = $slugify->slugify($request->title);
 
      // Define os atributos da instância
      $article->title = $request->title;
      $article->slug = $slug;
      $article->content = $request->content;
      $article->category_id = $request->category_id;

      // Invoca o método de salvamento da instancia no DB pelo Eloquent
      $article->save();

      // Retorna o status de sucesso da edição
      return response()->json(['status' => 'updated'], 204);
    }

    /**
     * Método responsável por apagar um artigo no DB
     *
     * @param Request $request
     * @param int $id
     * 
     * @return Response
     */
    public function destroy(int $id, Request $request)
    {
      // Busca o artigo pelo id
      $article = Article::find($id);

      // Se o artigo não existir retornamos o status 400
      if(empty($article)) 
      return response()->json(['status' => 'not found'], 400);
 
      // Invoca o método de exclusão da instancia no DB pelo Eloquent
      $article->delete();

      // Retorna o status de sucesso da exclusão
      return response()->json(['status' => 'deleted'], 204);
    }
}
