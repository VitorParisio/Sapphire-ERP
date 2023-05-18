<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\ItemVenda;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  function index(){
    return view('produtos.index');
  }

  function store(Request $request){

    $data      = $request->all();

    $validator = Validator::make($data, [
      'category_id'  => 'required|numeric|not_in:0|',
      'cod_barra'    => 'nullable|numeric|unique:products,cod_barra',
      'nome'         => 'required|regex:/^[a-z A-Z "-]+$/|min:2|max:100|unique:products,nome',
      'preco_compra' => 'required',
      'preco_venda'  => 'required',
      'estoque'      => 'required|numeric',
      'validade'     => 'date|nullable',
      'img'          => 'image|max:2048'
    ],
    [
      'category_id.required' => 'Preencher o campo "Categoria."',
      'category_id.numeric'  => 'Selecione uma categoria existente.',
      'category_id.not_in'   => 'Preencher o campo "Categoria."',
      'cod_barra.numeric'    => 'Digitar apenas números no campo "Cód. barra".',
      'cod_barra.unique'     => 'Código de barra já cadastrado.',
      'nome.unique'          => 'Produto já cadastrado.',
      'nome.required'        => 'Preencher o campo "Produto".',
      'nome.max'             => 'Excedeu o limite de 100 caracteres.',
      'nome.min'             => 'Palavra no mínimo com 2 caracteres.',
      'nome.regex'           => 'Digite apenas letras no campo "Produto".',
      'preco_compra.required'=> 'Preencher o campo "Preço custo."',
      'preco_venda.required' => 'Preencher o campo "Preço venda."',
      'estoque.required'     => 'Preencher o campo "Estoque."',
      'estoque.numeric'      => 'Digitar apenas números no campo "Estoque".',
      'validade.date'        => 'Data inválida.',
      'img.max'              => 'Tamanho máximo de 2MB (2048KB).'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->all()
        ]);
    }

    $preco_compra_formatado = str_replace('.', '', $request->preco_compra);
    $preco_venda_formatado  = str_replace('.', '', $request->preco_venda);

    $data['preco_compra'] = str_replace(',', '.', $preco_compra_formatado);
    $data['preco_venda']  = str_replace(',', '.', $preco_venda_formatado);
    $data['qtd_compra']   = $request->estoque;
    
    if ($request->img != null)
    {
      if ($request->img->isValid())
      {
        $nome_foto = Str::of($request->nome)->slug('-'). '.' .$request->img->getClientOriginalExtension();

        $foto = $request->img->storeAs('prod_img', $nome_foto);
        $data['img'] = $foto;
      }
    }

    Product::create($data);

    return response()->json(['message' => 'Produto cadastrado com sucesso.']);
  }

  function update(Request $request, $id)
  {
    
    $produto = Product::where('id', $id)
    ->first();
      
    $validator = Validator::make($request->all(), [
      'category_id'  => 'required|numeric|not_in:0|',
      'cod_barra'    => 'numeric|unique:products,cod_barra,'.$produto->id,
      'nome'         => 'required|regex:/^[a-z A-Z "-]+$/|min:2|max:100|unique:products,nome,'.$produto->id,
      'preco_compra' => 'required',
      'preco_venda'  => 'required',
      'estoque'      => 'required|numeric',
      'validade'     => 'date|nullable',
      'img'          => 'image|max:2048'
    ],
    [
      'category_id.required' => 'Preencher o campo "Categoria."',
      'category_id.numeric'  => 'Selecionar uma categoria existente.',
      'category_id.not_in'   => 'Preencher o campo "Categoria."',
      'cod_barra.numeric'    => 'Digitar apenas números no campo "Cód. barra".',
      'cod_barra.unique'     => 'Código de barra já cadastrado.',
      'nome.unique'          => 'Produto já cadastrado.',
      'nome.required'        => 'Preencher o campo "Produto".',
      'nome.max'             => 'Excedeu o limite de 100 caracteres.',
      'nome.min'             => 'Palavra no mínimo com 2 caracteres.',
      'nome.regex'           => 'Digite apenas letras no campo "Produto".',
      'preco_compra.required'=> 'Preencher o campo "Preço custo."',
      'preco_venda.required' => 'Preencher o campo "Preço venda."',
      'estoque.required'     => 'Preencher o campo "Estoque."',
      'estoque.numeric'      => 'Digitar apenas números no campo "Estoque".',
      'validade.date'        => 'Data inválida.',
      'img.max'              => 'Tamanho máximo de 2MB (2048KB).'
    ]);

    if ($validator->fails()) {
      return response()->json([
                  'error' => $validator->errors()->all()
              ]);
    }

    $preco_compra_formatado = str_replace('.', '', $request->preco_compra);
    $preco_venda_formatado  = str_replace('.', '', $request->preco_venda);

    $produto->cod_barra        = $request->input('cod_barra');
    $produto->nome             = $request->input('nome');
    $produto->preco_compra     = str_replace(',', '.', $preco_compra_formatado);
    $produto->preco_venda      = str_replace(',', '.', $preco_venda_formatado);
    $produto->estoque          = $request->input('estoque');
    $produto->descricao        = $request->input('descricao');
    $produto->category_id      = $request->input('category_id');
    $produto->validade         = $request->input('validade');

    if ($request->img != null)
    {
      if ($request->img->isValid())
      {
        if (Storage::exists($produto->img))
        {
          Storage::delete($produto->img);
        }

        $nome_foto = Str::of($request->nome)->slug('-'). '.' .$request->img->getClientOriginalExtension();

        $img_produto = $request->img->storeAs('prod_img', $nome_foto);
        $produto->img = $img_produto;
      }
    }
      $produto->update();

      return response()->json(['message' => 'Produto atualizado com sucesso!']);
  }

  function destroy($id){

    if (!$produto = Product::find($id))
      return redirect()->back();

    $produto->delete();

    return response()->json(['message' => 'Produto removido com sucesso!']);
  }

  function getProduct(Request $request){
    $query              = $request->get('query');
    $output             = '';
    $total_row          = '';

    if ($request->ajax())
    {
      if ($query != '')
      {
        $produto = Category::join('products', 'categories.id', '=', 'products.category_id')
        ->where('products.nome','LIKE','%'.$query.'%')
        ->get(); 
      }
      else
      {
        $produto = Category::join('products', 'categories.id', '=', 'products.category_id')
        ->orderBy('products.id', 'ASC')->get();
      }

      $total_row   = $produto->count();
      $total_itens = Product::all()->count();

      if ($total_row > 0)
      {
        foreach($produto as $row)
        {
          $img_prod = $row->img ? '<img src="storage/'.$row->img.'" alt="img_item" style="width:60px; height:60px; border-radius:30px;"/>' : '<i class="fas fa-image fa-3x" style="font-size:50px"></i>';
          $output .='
            <tr>
              <td>'.$img_prod.'</td>
              <td>'.$row->id.'</td>
              <td>'.ucfirst($row->nome).'</td>
              <td>R$ '.number_format($row->preco_compra, 2, ',', '.').'</td>
              <td>R$ '.number_format($row->preco_venda, 2, ',', '.').'</td>
              <td>'.$row->estoque.'</td>
              <td style="display:none;">'.$row->descricao.'</td>
              <td style="display:none;">'.$row->unidade.'</td>
              <td style="display:none;">'.$row->validade.'</td>
              <td style="display:none;">'.$row->cod_barra.'</td>
              <td style="display:none;">'.$row->category_id.'</td>
              <td style="display:none;">'.$row->categoria.'</td>
              <td><a href="#" class="dtls_btn"><i class="fas fa-eye" title="Detalhes do produto"></i></a></td>
              <td><a href="#" class="edt_btn"><i class="fas fa-edit" title="Editar produto"></i></a></td>
              <td><a href="#" class="del_btn"><i class="fas fa-trash" title="Excluir produto"></i></a></td>
            </tr>
          ';
        }
      }
      else
      {
        $output ='
          <tr>
            <td colspan="7" style="font-weight:100; font-size: 19px"><i>Produto não encontrado.</i></td>
          </tr>
        ';
      }

      $data = array(
        'output'            => $output,
        'total_product'     => $total_itens,
      );

      return response()->json($data);
    }
  }

  function searchItem(Request $request){
    $query = $request->get('query');
    $itens_agrupados = '';
    $total_row = '';

    if ($request->ajax())
    {
      if ($query != '')
      {
        $itens_agrupados = Product::join('item_vendas','products.id', '=', 'item_vendas.product_id')
        ->select('products.img','products.nome', 'products.qtd_compra', Product::raw('SUM(item_vendas.qtd) as total_itens'), Product::raw('SUM(item_vendas.sub_total) as sub_total'))
        ->where('products.nome','LIKE','%'.$query.'%')
        ->groupBy('products.img','products.nome', 'products.qtd_compra')
        ->get();
    
      }
      else
      {
        $itens_agrupados = Product::join('item_vendas','products.id', '=', 'item_vendas.product_id')
        ->select('products.img','products.nome', 'products.qtd_compra', Product::raw('SUM(item_vendas.qtd) as total_itens'), Product::raw('SUM(item_vendas.sub_total) as sub_total'))
        ->groupBy('products.img','products.nome', 'products.qtd_compra')
        ->get();
      }

      $total_row      = $itens_agrupados->count();

      if ($total_row > 0)
      {
        foreach($itens_agrupados as $row)
        {
          $img_prod = $row->img ? '<img src="storage/'.$row->img.'" alt="img_item" style="width:60px; height:60px; border-radius:30px;"/>' : '<i class="fas fa-image fa-3x" style="font-size:50px"></i>';
          $estoque  = $row->qtd_compra - $row->total_itens;
          $itens_agrupados .='
            <tr>
              <td>'.$img_prod.'</td>
              <td>'.ucfirst($row->nome).'</td>
              <td>'.$row->qtd_compra.'</td>
              <td>'.$row->total_itens.'</td>
              <td>'.$estoque.'</td>
              <td>R$ '.number_format($row->sub_total, 2, ',', '.').'</td>
            </tr>
          ';
        }
      }
      else
      {
        $itens_agrupados ='
          <tr>
            <td colspan="6" style="font-weight:100; font-size: 19px"><i>Item não encontrado.</i></td>
          </tr>
        ';
      }

      $data = array(
        'itens_agrupados' => $itens_agrupados,
      );

      return response()->json($data);
    }
  }

  function totalItem()
  {
    $itens_agrupados = Product::join('item_vendas','products.id', '=', 'item_vendas.product_id')
    ->select('products.img','products.nome', 'products.qtd_compra', Product::raw('SUM(item_vendas.qtd) as total_itens'), Product::raw('SUM(item_vendas.sub_total) as sub_total'))
    ->groupBy('products.img','products.nome', 'products.qtd_compra')
    ->get();

    $qtd_total_item = ItemVenda::all()->count();

     $data = array(
       'itens_agrupados' => $itens_agrupados,
       'qtd_total_item'  => $qtd_total_item
     );

     return response()->json($data);
  }

  function notifications(){
    $produtos_count = Product::where('estoque', '<=', 5)->count();
    $estoque_baixo = false;

    return response()->json(['produtos_count' => $produtos_count, 'estoque_baixo' => $estoque_baixo]);
  }

}
