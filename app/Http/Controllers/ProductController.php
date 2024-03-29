<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ItemVendaNfe;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  function index(){
    return view('produtos.index');
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
        ->orderBy('products.nome', 'ASC')
        ->get(); 

        $total_itens = $produto->count();
      }
      else
      {
        $produto = Category::join('products', 'categories.id', '=', 'products.category_id')
        ->orderBy('products.nome', 'ASC')->get();
       
        $total_itens = $produto->count();
      }
   
      $total_row = $produto->count();
      
      if ($total_row > 0)
      {
        foreach($produto as $row)
        {
          $img_prod       = $row->img ? '<img src="storage/'.$row->img.'" alt="img_item" style="width:55px; height:30px;"/>' : '<img src="img/sem_imagem.png" alt="img_item" style="width:55px; height:30px;"/>';
          $cod_barra      = $row->cod_barra == null ? "Não informado" : $row->cod_barra;
          $estoque_minimo = $row->estoque_minimo == null ? "Não informado" : $row->estoque_minimo;
          $qtd_atacado    = $row->qtd_atacado == null ? "Não informado" : $row->qtd_atacado;
          $descricao      = $row->descricao == null ? "Não informado" : $row->descricao;
          $validade       = $row->validade == null ? "Não informado" : $row->validade;
          $ncm            = $row->ncm == null ? "Não informado" : $row->ncm;
          $cest           = $row->cest == null ? "Não informado" : $row->cest;
          $extipi         = $row->extipi == null ? "Não informado" : $row->extipi; 

          $output .='
            <tr>
              <td data-label="#">'.$img_prod.'</td>
              <td data-label="Código">'.$row->id.'</td>
              <td data-label="Categoria">'.strtoupper($row->categoria).'</td>
              <td data-label="Produto">'.strtoupper($row->nome).'</td>
              <td data-label="Preço venda">R$ '.number_format($row->preco_venda, 2, ',', '.').'</td>
              <td data-label="Estoque">'.$row->estoque.'</td>
              <td style="display:none;">'.$qtd_atacado.'</td>
              <td style="display:none;">R$ '.number_format($row->preco_atacado, 2, ',', '.').'</td>
              <td style="display:none;">'.$descricao.'</td>
              <td style="display:none;">'.$row->ucom.'</td>
              <td style="display:none;">'.$validade.'</td>
              <td style="display:none;">'.$cod_barra.'</td>
              <td style="display:none;">'.$estoque_minimo.'</td>
              <td style="display:none;">R$ '.number_format($row->preco_compra, 2, ',', '.').'</td>
              <td style="display:none;">R$ '.number_format($row->preco_minimo, 2, ',', '.').'</td>
              <td style="display:none;">'.$ncm.'</td>
              <td style="display:none;">'.$cest.'</td>
              <td style="display:none;">'.$extipi.'</td>
              <td style="display:none;">5101</td>
              <td style="display:none;">0 - Nacional</td>
              <td style="display:none;">102 - Simples Nacional sem permissão de crédito</td>
              <td style="display:none;">R$ '.number_format($row->margem_lucro, 2, ',', '.').'</td>
              <td style="display:none;">'.$row->margem_lucro_per.'%</td>
              <td data-label="Detalhes"><a href="javascript:void(0);" class="dtls_btn"><i class="fas fa-eye fa-sm" title="Detalhes do produto"></i></a></td>
              <td data-label="Editar"><a href="javascript:void(0);" class="edt_btn" style="color: gray;"><i class="fas fa-edit fa-sm" title="Editar produto"></i></a></td>
              <td data-label="Excluir"><a href="javascript:void(0);" class="del_btn" style="color: red;"><i class="fas fa-times-circle fa-sm" title="Deletar produto"></i></a></td>
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

  function store(Request $request){

    $data = $request->all();
    
    $data['ucom']  = "UNID";
    $data['utrib'] = "UNID";

    $validator = Validator::make($data, [
      'category_id'    => 'required|numeric|not_in:0|',
      'cod_barra'      => 'nullable|regex:/^[0-9]+$/|unique:products,cod_barra',
      'ncm'            => 'nullable|regex:/^[0-9]+$/',
      'cest'           => 'nullable|regex:/^[0-9]+$/',
      'nome'           => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-,]+$/|min:2|max:100|unique:products,nome',
      'preco_compra'   => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_venda'    => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_minimo'   => 'nullable|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_atacado'  => 'nullable|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'qtd_atacado'    => 'nullable|numeric',
      'estoque'        => 'required|numeric',
      'validade'       => 'date|nullable',
      'extipi'         => 'nullable|numeric',
      'estoque_minimo' => 'required|numeric',
      'descricao'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-,]*$/',
      'img'            => 'nullable|image|max:2048|mimes:jpg,jpeg,png'
    ],
    [
      'category_id.required'   => 'Campo "Categoria" deve ser preenchido.',
      'category_id.numeric'    => 'Selecione uma categoria existente.',
      'category_id.not_in'     => 'Campo "Categoria" deve ser preenchido.',
      'cod_barra.regex'        => 'Digitar apenas números no campo "EAN (Código Barra)".',
      'cod_barra.unique'       => 'EAN (Código Barra) já cadastrado.',
      'ncm.regex'              => 'Digitar apenas números no campo "NCM".',
      'cest.regex'             => 'Digitar apenas números no campo "CEST".',
      'nome.unique'            => 'Produto já cadastrado.',
      'nome.required'          => 'Campo "Produto" deve ser preenchido".',
      'nome.max'               => 'Excedeu o limite de 100 caracteres.',
      'nome.min'               => 'Poucos dígitos no campo "Produto".',
      'nome.regex'             => 'Digitar apenas letras e/ou números no campo "Produto".',
      'preco_compra.required'  => 'Campo "Preço custo" deve ser preenchido".',
      'preco_compra.regex'     => 'Valor incorreto no campo "Preço custo".',
      'preco_venda.required'   => 'Campo "Preço venda" deve ser preenchido".',
      'preco_venda.regex'      => 'Valor incorreto no campo "Preço venda".',
      'preco_minimo.regex'     => 'Valor incorreto no campo "Preço mínimo".',
      'preco_atacado.regex'    => 'Valor incorreto no campo "Preço Atacado".',
      'qtd_atacado.numeric'    => 'Digitar apenas números no campo "Qtd. Atacado".',
      'estoque.required'       => 'Campo "Estoque atual" deve ser preenchido."',
      'estoque.numeric'        => 'Digitar apenas números no campo "Estoque atual".',
      'validade.date'          => 'Data inválida.',
      'img.max'                => 'Tamanho máximo de 2MB (2048KB).',
      'estoque_minimo.required'=> 'Campo "Estoque mínimo" deve ser preenchido.',
      'extipi.numeric'         => 'Digitar apenas números no campo "IPI".',
      'estoque_minimo.numeric' => 'Digitar apenas números no campo "Estoque Mínimo".',
      'descricao'              => 'Digitar apenas letras e/ou números no campo "Descrição".',
      'img.mimes'              => 'Campo "Imagem" aceita apenas as extensões .jpg, .jpeg e .png',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->all()
        ]);
    }
 
    if ($data['descricao'] == null)
    {
      $data['descricao'] = "Não informado";
    } 

    if ($data['preco_minimo'] == null)
    {
      $data['preco_minimo'] = 0.00;
    } else
    {
      $preco_minimo_formatado = str_replace('.', '', $request->preco_minimo);
      $data['preco_minimo']   = str_replace(',', '.', $preco_minimo_formatado);
    }

    if ($data['preco_atacado'] == null)
    {
      $data['preco_atacado'] = 0.00;
    } else
    {
      $preco_atacado_formatado = str_replace('.', '', $request->preco_atacado);
      $data['preco_atacado']   = str_replace(',', '.', $preco_atacado_formatado);
    }

    $preco_compra_formatado = str_replace('.', '', $request->preco_compra);
    $preco_venda_formatado  = str_replace('.', '', $request->preco_venda);
    $preco_lucro_formatado  = str_replace('.', '', $request->margem_lucro);
    $vuntrib_formatado      = str_replace('.', '', $request->vuntrib);
    $total_compra           = str_replace(',', '.', $request->preco_compra) * (float)$request->estoque;
    
    $data['preco_compra']     = str_replace(',', '.', $preco_compra_formatado);
    $data['preco_venda']      = str_replace(',', '.', $preco_venda_formatado);
    $data['margem_lucro']     = str_replace(',', '.', $preco_lucro_formatado);
    $data['vuntrib']          = str_replace(',', '.', $vuntrib_formatado);
    $data['qtd_compra']       = $request->estoque;
    $data['margem_lucro_per'] = $request->margem_lucro_per;
    $data['total_compra']     = $total_compra;
    $data['qtd_atacado']      = $request->qtd_atacado;
    
    if ($request->img != null)
    {
      if ($request->img->isValid())
      {
        $nome_foto = uniqid(date('YmdHis')).'.'.$request->img->getClientOriginalExtension();

        $foto = $request->img->storeAs('prod_img', $nome_foto);
        $data['img'] = $foto;
      }
    }

    Product::create($data);

    return response()->json(['message' => 'Produto cadastrado com sucesso.']);
  }

  function update(Request $request, $id)
  {
    if (!$produto = Product::find($id))
        return redirect()->back();
    
    $produto = Product::where('id', $id)
    ->first();

    $validator = Validator::make($request->all(), [
      'category_id'            => 'required|numeric|not_in:0|',
      'cod_barra'              => 'nullable|regex:/^[0-9]+$/|unique:products,cod_barra,'.$produto->id,
      'ncm'                    => 'nullable|regex:/^[0-9]+$/',
      'cest'                   => 'nullable|regex:/^[0-9]+$/',
      'nome'                   => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-,]+$/|min:2|max:100|unique:products,nome,'.$produto->id,
      'preco_compra'           => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_venda'            => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_minimo'           => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_atacado'          => 'nullable|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'qtd_atacado'            => 'nullable|numeric',
      'estoque'                => 'required|numeric',
      'validade'               => 'date|nullable',
      'extipi'                 => 'nullable|numeric',
      'estoque_minimo.required'=> 'Campo "Estoque mínimo" deve ser preenchido.',
      'estoque_minimo'         => 'required|numeric',
      'descricao'              => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-,]*$/',
      'img'                    => 'image|max:2048|mimes:jpg,jpeg,png',
      'entrada'                => 'required'
    ],
    [
      'category_id.required'   => 'Campo "Categoria" deve ser preenchido.',
      'category_id.numeric'    => 'Selecione uma categoria existente.',
      'category_id.not_in'     => 'Campo "Categoria" deve ser preenchido.',
      'cod_barra.regex'        => 'Digitar apenas números no campo "EAN (Código Barra)".',
      'cod_barra.unique'       => 'EAN (Código Barra) já cadastrado.',
      'ncm.regex'              => 'Digitar apenas números no campo "NCM".',
      'cest.regex'             => 'Digitar apenas números no campo "CEST".',
      'nome.unique'            => 'Produto já cadastrado.',
      'nome.required'          => 'Campo "Produto" deve ser preenchido.',
      'nome.max'               => 'Excedeu o limite de 100 caracteres.',
      'nome.min'               => 'Poucos dígitos no campo "Produto".',
      'nome.regex'             => 'Digitar apenas letras no campo "Produto".',
      'preco_compra.required'  => 'Campo "Preço custo" deve ser preenchido.',
      'preco_compra.regex'     => 'Valor incorreto no campo "Preço custo".',
      'preco_venda.required'   => 'Campo "Preço venda" deve ser preenchido.',
      'preco_venda.regex'      => 'Valor incorreto no campo "Preço venda".',
      'preco_minimo.required'  => 'Campo "Preço mínimo" deve ser preenchido.',
      'preco_minimo.regex'     => 'Valor incorreto no campo "Preço mínimo".',
      'preco_atacado.regex'    => 'Valor incorreto no campo "Preço Atacado".',
      'qtd_atacado.numeric'    => 'Digitar apenas números no campo "Qtd. Atacado".',
      'estoque.required'       => 'Campo "Estoque atual" deve ser preenchido.',
      'estoque.numeric'        => 'Digitar apenas números no campo "Estoque atual".',
      'validade.date'          => 'Data inválida.',
      'img.max'                => 'Tamanho máximo de 2MB (2048KB).',
      'extipi.numeric'         => 'Digitar apenas números no campo "IPI".',
      'estoque_minimo.required'=> 'Campo "Estoque mínimo" deve ser preenchido.',
      'estoque_minimo.numeric' => 'Digitar apenas números no campo "Estoque Mínimo".',
      'descricao'              => 'Digitar apenas letras e/ou números no campo "Descrição".',
      'img.mimes'              => 'Campo "Imagem" aceita apenas as extensões .jpg, .jpeg e .png',
      'entrada.required'       => 'Campo "Entrada" deve ser preenchido.',
    ]);

    if ($validator->fails()) {
      return response()->json([
                  'error' => $validator->errors()->all()
              ]);
    }

    $produto->cod_barra        = $request->input('cod_barra');
    $produto->ceantrib         = $request->input('ceantrib');
    $produto->nome             = $request->input('nome');
    $produto->estoque_minimo   = $request->input('estoque_minimo');
    $produto->qtd_atacado      = $request->input('qtd_atacado');
    $produto->descricao        = $request->input('descricao');
    $produto->category_id      = $request->input('category_id');
    $produto->validade         = $request->input('validade');
    $produto->ncm              = $request->input('ncm');
    $produto->cest             = $request->input('cest');
    $produto->extipi           = $request->input('extipi');
    $produto->margem_lucro_per = $request->input('margem_lucro_per');
    
    $produto->preco_compra      = str_replace(',', '.', $request->preco_compra);
    $produto->preco_venda       = str_replace(',', '.', $request->preco_venda);
    $produto->margem_lucro      = str_replace(',', '.', $request->margem_lucro);
    $produto->preco_atacado     = str_replace(',', '.', $request->preco_atacado);
    $produto->vuntrib           = str_replace(',', '.', $request->vuntrib);
    $produto->preco_minimo      = str_replace(',', '.', $request->preco_minimo);
    
    if ($request->entrada == 'no')
    {
      $produto->estoque    = $request->input('estoque');
      $produto->qtd_compra = $request->input('estoque');

      $produto->total_compra = str_replace(',', '.', $request->preco_compra) * (float)$request->input('estoque');
    }
    else
    {
      $entrada_estoque     = $produto->estoque + $request->input('estoque');
      $produto->estoque    = $entrada_estoque;
      $produto->qtd_compra = $entrada_estoque;
      
      $produto->total_compra = $produto->total_compra + (str_replace(',', '.', $request->preco_compra) * (float)$request->input('estoque'));
    }

    if ($request->img != null)
    {
      if ($request->img->isValid())
      {
        if (Storage::exists($produto->img))
        {
          Storage::delete($produto->img);
        }

        $img_produto  = $request->img->store('prod_img');
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

  function searchItem(Request $request){
    $query = $request->get('query');
    $itens_agrupados = '';
    $total_row = '';

    if ($request->ajax())
    {
      if ($query != '')
      {
        $itens_agrupados = Product::join('item_venda_nves','products.id', '=', 'item_venda_nves.product_id')
        ->select('products.img','products.nome', 'products.qtd_compra', Product::raw('SUM(item_venda_nves.qtd) as total_itens'), Product::raw('SUM(item_venda_nves.sub_total) as sub_total'))
        ->where('products.nome','LIKE','%'.$query.'%')
        ->groupBy('products.img','products.nome', 'products.qtd_compra')
        ->get();
    
      }
      else
      {
        $itens_agrupados = Product::join('item_venda_nves','products.id', '=', 'item_venda_nves.product_id')
        ->select('products.img','products.nome', 'products.qtd_compra', Product::raw('SUM(item_venda_nves.qtd) as total_itens'), Product::raw('SUM(item_venda_nves.sub_total) as sub_total'))
        ->groupBy('products.img','products.nome', 'products.qtd_compra')
        ->get();
      }

      $total_row      = $itens_agrupados->count();

      if ($total_row > 0)
      {
        foreach($itens_agrupados as $row)
        {
          $img_prod = $row->img ? '<img src="storage/'.$row->img.'" alt="img_item" style="width:60px; height:60px; border-radius:30px;"/>' : '<i class="fa-brands fa-product-hunt"></i>';
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
    $itens_agrupados = Product::join('item_venda_nves','products.id', '=', 'item_venda_nves.product_id')
    ->select('products.img','products.nome', 'products.qtd_compra', Product::raw('SUM(item_venda_nves.qtd) as total_itens'), Product::raw('SUM(item_venda_nves.sub_total) as sub_total'))
    ->groupBy('products.img','products.nome', 'products.qtd_compra')
    ->get();

    $qtd_total_item = ItemVendaNfe::all()->count();

     $data = array(
       'itens_agrupados' => $itens_agrupados,
       'qtd_total_item'  => $qtd_total_item
     );

     return response()->json($data);
  }

  function notifications(){

    $produtos_estoque_minimo = Product::select('nome')
    ->whereColumn('estoque', '<=','estoque_minimo')
    ->get();

    $produtos_count = Product::whereColumn('estoque', '<=','estoque_minimo')->count();
 
    $estoque_baixo                = false;
    $estoque_baixo_msg            = '';
    $lista_produtos_estoque_baixo = '';

    $estoque_baixo_msg ='<span style="color: red; font-style: italic">Estoque baixo: '.$produtos_count.'</span><hr>';
    if ($produtos_count > 0)
    {
      foreach($produtos_estoque_minimo as $values)
      {
        $lista_produtos_estoque_baixo .='<li style="border-bottom: 1px solid; border-color: rgba(0,0,0,0.2); padding: 5px;">'.ucfirst($values->nome).'</li>';
      }
    } 
    else {
        $lista_produtos_estoque_baixo ='<li style="padding: 5px; color: gray; font-family:serif;"><i>Sem informações no momento.</i></li>';
    }

    $data = array(
            'produtos_estoque_minimo'      => $produtos_estoque_minimo, 
            'produtos_count'               => $produtos_count, 
            'estoque_baixo'                => $estoque_baixo, 
            'estoque_baixo_msg'            => $estoque_baixo_msg,
            'lista_produtos_estoque_baixo' => $lista_produtos_estoque_baixo
          );

    return response()->json($data);
  }

  public function estoqueBaixo()
  {
    $produtos_estoque_baixo = Product::select('id', 'nome','qtd_compra','estoque','estoque_minimo')
    ->whereColumn('estoque', '<=','estoque_minimo')
    ->get();

    $produtos_count = Product::whereColumn('estoque', '<=','estoque_minimo')->count();

    $dados_produtos_estoque_baixo = '';
   
    foreach($produtos_estoque_baixo as $values)
    {
      $dados_produtos_estoque_baixo .=
          '<tr>
              <td data-label="ID" style="padding: 5px;">'.$values->id.'</td>
              <td data-label="Produto" style="padding: 5px;"><a class="select_produto_estoque_baixo" href="javascript:void(0);">'.ucfirst($values->nome).'</a></td>
              <td data-label="Estoque atual" style="padding: 5px;">'.$values->estoque.'</td>
              <td data-label="Estoque mínimo" style="padding: 5px;">'.$values->estoque_minimo.'</td>
          </tr>';
    }

    return response()->json(['dados_produtos_estoque_baixo' => $dados_produtos_estoque_baixo, 'produtos_count' => $produtos_count]);
  }

}
