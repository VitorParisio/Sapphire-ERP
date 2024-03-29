<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    function index()
    {
        $categorias      = Category::get();
        $categoria_count = Category::all()->count();

        foreach($categorias as $categoria)
        {
            $categoria->categoria = strtoupper($categoria->categoria);
            
            if ($categoria->descricao == null)
            {
                $categoria->descricao = "Sem descrição";
            }  
        }

        if ($categoria_count > 0)
        {
            return response()->json(['categorias' => $categorias, 'categoria_count' => $categoria_count]);
        }
        
        return response()->json(['texto' => 'Categoria não encontrada.']);
    }

    function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'categoria' => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]+$/|min:2|max:100|unique:categories,categoria,',
            'descricao' => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]+$/|max:200'
        ],
        [
            'categoria.required' => 'Campo "Categoria" deve ser preenchido.',
            'categoria.unique'   => 'Categoria já cadastrada.',
            'categoria.regex'    => 'Digitar apenas letras no campo "Categoria".',
            'categoria.min'      => 'Palavra no mínimo com 2 caracteres.',
            'categoria.max'      => 'Excedeu o limite de 100 caracteres.',
            'descricao.regex'    => 'Digite apenas letras e/ou números no campo "Descrição".',
            'descricao.max'      => 'Excedeu o limite de 200 caracteres.',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
        
        $categorias = $request->all();
        $categoria  = Category::create($categorias);

        $categoria->save();

        return response()->json(['message' => 'Categoria cadastrada com sucesso.']);
    }

    function update(Request $request, $id)
    {

        if (!$categoria = Category::find($id))
            return redirect()->back();

        $validator = Validator::make($request->all(), [
            'categoria' => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]+$/|min:2|max:100|unique:categories,categoria,'.$categoria->id,
            'descricao' => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]+$/|max:200'
        ],
        [
            'categoria.required' => 'Campo "Categoria" deve ser preenchido.',
            'categoria.unique'   => 'Categoria já cadastrada.',
            'categoria.regex'    => 'Digite apenas letras e/ou números no campo "Categoria".',
            'categoria.min'      => 'Palavra no mínimo com 2 caracteres.',
            'categoria.max'      => 'Excedeu o limite de 100 caracteres.',
            'descricao.regex'    => 'Digite apenas letras e/ou números no campo "Descrição".',
            'descricao.max'      => 'Excedeu o limite de 200 caracteres.',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        $categoria->categoria = $request->input('categoria');
        $categoria->descricao = $request->input('descricao');

        $categoria->update();

        return response()->json(['message' => 'Categoria atualizada com sucesso!']);
    }

    function destroy($id){
        if (!$categoria = Category::find($id))
            return redirect()->back();
        
        $categoria->delete();

        return response()->json(['message' => 'Categoria removida com sucesso!']);
    }

    function selectCategoria($id = '')
    {    
        $categorias      = Category::get();
        $categoria       = '';
        $categoria_query = '';
        $first_option    = '';
        $dados_categoria = [];
        
        if ($id > 0)
        {
            $categoria = Category::join('products', 'categories.id', '=', 'products.category_id')
            ->where('products.id', $id)->first();

            $categoria_query = $categoria->categoria;
            $first_option    = '<option value="'.$categoria->category_id.'" selected>'.$categoria->categoria.'</option>';
        }
       
        foreach ($categorias as $key => $value)
        {

            $dados_categoria[$value->id] = $value->categoria; 
        
            if ($dados_categoria[$value->id] == $categoria_query)
            {
                unset($dados_categoria[$value->id]);
            }
        }
       
        return response()->json(['dados_categoria' => $dados_categoria, 'id' => $id, 'first_option' => $first_option]);
    }
}
