<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use \Cocur\Slugify\Slugify;

class CategoryController extends Controller
{
    /**
     * Retorna todos as categorias ou apenas as pesquisados
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request){  
        // Invoca o método do Eloquente que retorna todas as categorias
        $categorys = Category::all();

        // Retorna as categorias
        return response()->json($categorys, 200);
    }

    /**
     * Método responsável por retornar uma categoria no DB
     *
     * @param Request $request
     * @param int $id
     * 
     * @return Response
     */
    public function show(int $id, Request $request)
    {
      // Busca a categoria pelo id
      $category = Category::find($id);

      // Se a categoria não existir retornamos o status 400
      if(empty($category)) 
      return response()->json(['status' => 'not found'], 400);

      // Retorna a categoria
      return response()->json($category, 200);
    }

    /**
     * Método responsável por cadastrar a categoria no DB
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
      // Instancia a classe geradora de slug
      $slugify = new Slugify();
      // Gera o slug do título da categoria
      $slug = $slugify->slugify($request->name);

      // Instancia a entidade criada pelo Eloquent
      $category = new Category;
 
      // Define os atributos da instância
      $category->name = $request->name;
      $category->slug = $slug;

      // Invoca o método de inserção da instancia no DB pelo Eloquent
      $category->save();

      // Retorna o status de sucesso do cadastro
      return response()->json(['status' => 'created'], 201);
    }

    /**
     * Método responsável por editar uma categoria no DB
     *
     * @param Request $request
     * @param int $id
     * 
     * @return Response
     */
    public function update(int $id, Request $request)
    {
      // Busca a categoria pelo id
      $category = Category::find($id);

      // Se a categoria não existir retornamos o status 400
      if(empty($category)) 
      return response()->json(['status' => 'not found'], 400);

      // Instancia a classe geradora de slug
      $slugify = new Slugify();
      // Gera o slug do título da categoria
      $slug = $slugify->slugify($request->name);
 
      // Define os atributos da instância
      $category->name = $request->name;
      $category->slug = $slug;

      // Invoca o método de salvamento da instancia no DB pelo Eloquent
      $category->save();

      // Retorna o status de sucesso da edição
      return response()->json(['status' => 'updated'], 204);
    }

    /**
     * Método responsável por apagar uma categoria no DB
     *
     * @param Request $request
     * @param int $id
     * 
     * @return Response
     */
    public function destroy(int $id, Request $request)
    {
      // Busca a categoria pelo id
      $category = Category::find($id);

      // Se a categoria não existir retornamos o status 400
      if(empty($category)) 
      return response()->json(['status' => 'not found'], 400);
 
      // Invoca o método de exclusão da instancia no DB pelo Eloquent
      $category->delete();

      // Retorna o status de sucesso da exclusão
      return response()->json(['status' => 'deleted'], 204);
    }
}
