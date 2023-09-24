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
        ->get(); 

        $total_itens = $produto->count();
      }
      else
      {
        $produto = Category::join('products', 'categories.id', '=', 'products.category_id')
        ->orderBy('products.id', 'ASC')->get();
        
        $total_itens = $produto->count();
      }

      $total_row   = $produto->count();
      
      if ($total_row > 0)
      {
        foreach($produto as $row)
        {
          $img_prod       = $row->img ? '<img src="storage/'.$row->img.'" alt="img_item" style="width:55px; height:30px;"/>' : '<img src="img/sem_imagem.jpg" alt="img_item" style="width:55px; height:30px;"/>';
          $cod_barra      = $row->cod_barra == null ? "Não informado" : $row->cod_barra;
          $estoque_minimo = $row->estoque_minimo == null ? "Não informado" : $row->estoque_minimo;
          $descricao      = $row->descricao == null ? "Não informado" : $row->descricao;
          $validade       = $row->validade == null ? "Não informado" : $row->validade;
          $ncm            = $row->ncm == null ? "Não informado" : $row->ncm;
          $cest           = $row->cest == null ? "Não informado" : $row->cest;
          $extipi         = $row->extipi == null ? "Não informado" : $row->extipi; 
          $cfop           = $row->cfop == null ? "Não informado" : $row->cfop; 

          $output .='
            <tr>
              <td>'.$img_prod.'</td>
              <td>'.$row->id.'</td>
              <td>'.ucfirst($row->categoria).'</td>
              <td>'.ucfirst($row->nome).'</td>
              <td>R$ '.number_format($row->preco_venda, 2, ',', '.').'</td>
              <td>'.$row->estoque.'</td>
              <td style="display:none;">'.$descricao.'</td>
              <td style="display:none;">'.$row->ucom.'</td>
              <td style="display:none;">'.$validade.'</td>
              <td style="display:none;">'.$cod_barra.'</td>
              <td style="display:none;">'.$row->category_id.'</td>
              <td style="display:none;">'.$row->categoria.'</td>
              <td style="display:none;">'.$estoque_minimo.'</td>
              <td style="display:none;">R$ '.number_format($row->preco_compra, 2, ',', '.').'</td>
              <td style="display:none;">R$ '.number_format($row->preco_minimo, 2, ',', '.').'</td>
              <td style="display:none;">'.$ncm.'</td>
              <td style="display:none;">'.$cest.'</td>
              <td style="display:none;">'.$extipi.'</td>
              <td style="display:none;">'.$cfop.'</td>
              <td style="display:none;">0 - Nacional</td>
              <td style="display:none;">102 - Simples Nacional sem permissão de crédito</td>
              <td><a href="#" class="dtls_btn"><i class="fas fa-eye fa-sm" title="Detalhes do produto"></i></a></td>
              <td><a href="#" class="edt_btn" style="color: gray;"><i class="fas fa-edit fa-sm" title="Editar produto"></i></a></td>
              <td><a href="#" class="del_btn" style="color: red;"><i class="fas fa-times-circle fa-sm" title="Deletar produto"></i></a></td>
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
      'cod_barra'      => 'nullable|numeric|unique:products,cod_barra',
      'ncm'            => 'nullable|numeric|min:8',
      'cest'           => 'nullable|numeric|min:7',
      'nome'           => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]+$/|min:2|max:100|unique:products,nome',
      'preco_compra'   => 'nullable|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_venda'    => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_minimo'   => 'nullable|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'estoque'        => 'required|numeric',
      'ucom'           => 'required|regex:/^[a-z A-Z]+$/',
      'validade'       => 'date|nullable',
      'extipi'         => 'nullable|numeric',
      'estoque_minimo' => 'nullable|numeric',
      'descricao'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]*$/',
      'img'            => 'image|max:2048|mimes:jpg,jpeg,png'
    ],
    [
      'category_id.required'   => 'Campo "Categoria" deve ser preenchido.',
      'category_id.numeric'    => 'Selecione uma categoria existente.',
      'category_id.not_in'     => 'Campo "Categoria" deve ser preenchido".',
      'cod_barra.numeric'      => 'Digitar apenas números no campo "EAN (Código Barra)".',
      'cod_barra.unique'       => 'EAN (Código Barra) já cadastrado.',
      'ncm.numeric'            => 'Digitar apenas números no campo "NCM".',
      'ncm.min'                => 'Necessário 8 dígitos no campo "NCM".',
      'ncm.max'                => 'Necessário 8 dígitos no campo "NCM".',
      'nome.unique'            => 'Produto já cadastrado.',
      'nome.required'          => 'Campo "Produto" deve ser preenchido".',
      'nome.max'               => 'Excedeu o limite de 100 caracteres.',
      'nome.min'               => 'Poucos dígitos no campo "Produto".',
      'nome.regex'             => 'Digitar apenas letras no campo "Produto".',
      'preco_compra.regex'     => 'Valor incorreto no campo "Preço custo".',
      'preco_venda.required'   => 'Campo "Preço venda" deve ser preenchido".',
      'preco_venda.regex'      => 'Valor incorreto no campo "Preço venda".',
      'preco_minimo.regex'     => 'Valor incorreto no campo "Preço mínimo".',
      'estoque.required'       => 'Campo "Estoque atual" deve ser preenchido."',
      'estoque.numeric'        => 'Digitar apenas números no campo "Estoque atual".',
      'ucom.required'          => 'Campo "Unid. Comercial" deve ser preenchido."',
      'ucom.regex'             => 'Digitar apenas letras no campo "Unid. Comercial".',
      'validade.date'          => 'Data inválida.',
      'img.max'                => 'Tamanho máximo de 2MB (2048KB).',
      'extipi.numeric'         => 'Digitar apenas números no campo "EXT IPI".',
      'estoque_minimo.numeric' => 'Digitar apenas números no campo "Estoque Mínimo".',
      'descricao'              => 'Digitar apenas letras e/ou números no campo "Descrição".',
      'img.mimes'              => 'Campo "Imagem" aceita apenas as extensões .jpg, .jpeg e .png',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->all()
        ]);
    }

    if ($data['preco_compra'] == null)
    {
      $data['preco_compra'] = 0.00;
    } else
    {
      $preco_compra_formatado = str_replace('.', '', $request->preco_compra);
      $data['preco_compra']   = str_replace(',', '.', $preco_compra_formatado);
    }

    if ($data['preco_minimo'] == null)
    {
      $data['preco_minimo'] = 0.00;
    } else
    {
      $preco_minimo_formatado = str_replace('.', '', $request->preco_minimo);
      $data['preco_minimo']   = str_replace(',', '.', $preco_minimo_formatado);
    }

   
    $preco_venda_formatado  = str_replace('.', '', $request->preco_venda);
    $vuntrib_formatado      = str_replace('.', '', $request->vuntrib);
    
    $data['preco_venda']  = str_replace(',', '.', $preco_venda_formatado);
    $data['vuntrib']      = str_replace(',', '.', $vuntrib_formatado);
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
    if (!$produto = Product::find($id))
        return redirect()->back();
    
    $produto = Product::where('id', $id)
    ->first();

    $validator = Validator::make($request->all(), [
      'category_id'    => 'required|numeric|not_in:0|',
      'cod_barra'      => 'nullable|numeric|unique:products,cod_barra'.$produto->id,
      'ceantrib'       => 'nullable|numeric|unique:products,ceantrib'.$produto->id,
      'ncm'            => 'numeric|min:8',
      'nome'           => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]+$/|min:2|max:100|unique:products,nome'.$produto->id,
      'preco_compra'   => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_venda'    => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'vuntrib'        => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'preco_minimo'   => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
      'estoque'        => 'required|numeric',
      'qtrib'          => 'required|numeric',
      'ucom'           => 'required|regex:/^[a-z A-Z]+$/',
      'utrib'          => 'required|regex:/^[a-z A-Z]+$/',
      'validade'       => 'date|nullable',
      'extipi'         => 'nullable|numeric',
      'estoque_minimo' => 'numeric',
      'descricao'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]*$/',
      'img'            => 'image|max:2048|mimes:jpg,jpeg,png'
    ],
    [
      'category_id.required'   => 'Campo "Categoria" deve ser preenchido".',
      'category_id.numeric'    => 'Selecione uma categoria existente.',
      'category_id.not_in'     => 'Campo "Categoria" deve ser preenchido".',
      'cod_barra.numeric'      => 'Digitar apenas números no campo "EAN".',
      'cod_barra.unique'       => 'EAN já cadastrado.',
      'ceantrib.numeric'       => 'Digitar apenas números no campo "EAN Unid. Tributável".',
      'ceantrib.unique'        => 'EAN Unid. Tributável já cadastrado.',
      'ncm.numeric'            => 'Digitar apenas números no campo "NCM".',
      'ncm.min'                => 'Necessário 8 dígitos no campo "NCM".',
      'ncm.max'                => 'Necessário 8 dígitos no campo "NCM".',
      'nome.unique'            => 'Produto já cadastrado.',
      'nome.required'          => 'Campo "Produto" deve ser preenchido".',
      'nome.max'               => 'Excedeu o limite de 100 caracteres.',
      'nome.min'               => 'Poucos dígitos no campo "Produto".',
      'nome.regex'             => 'Digitar apenas letras no campo "Produto".',
      'preco_compra.required'  => 'Campo "Valor custo" deve ser preenchido"',
      'preco_compra.regex'     => 'Valor incorreto no campo "Valor custo."',
      'preco_venda.required'   => 'Campo "Valor venda" deve ser preenchido".',
      'preco_venda.regex'      => 'Valor incorreto no campo "Valor venda".',
      'vuntrib.required'       => 'Campo "Valor Unid. Tributável" deve ser preenchido".',
      'vuntrib.regex'          => 'Valor incorreto no campo "Valor Unid. Tributável".',
      'preco_minimo.required'  => 'Campo "Valor Unid. Tributável" deve ser preenchido".',
      'preco_minimo.regex'     => 'Valor incorreto no campo "Valor Unid. Tributável".',
      'estoque.required'       => 'Campo "Qtd. Comercial" deve ser preenchido."',
      'estoque.numeric'        => 'Digitar apenas números no campo "Qtd. Comercial".',
      'qtrib.required'         => 'Campo "Qtd. Tributável" deve ser preenchido."',
      'qtrib.numeric'          => 'Digitar apenas números no campo "Qtd. Tributável".',
      'ucom.required'          => 'Campo "Unid. Comercial" deve ser preenchido."',
      'ucom.regex'             => 'Digitar apenas letras no campo "Unid. Comercial".',
      'utrib.required'         => 'Campo "Unid. Tributável" deve ser preenchido."',
      'utrib.regex'            => 'Digitar apenas letras no campo "Unid. Tributável".',
      'validade.date'          => 'Data inválida.',
      'img.max'                => 'Tamanho máximo de 2MB (2048KB).',
      'extipi.numeric'         => 'Digitar apenas números no campo "EXT IPI".',
      'estoque_minimo.numeric' => 'Digitar apenas números no campo "Estoque Mínimo".',
      'descricao'              => 'Digitar apenas letras e/ou números no campo "Descrição".',
      'img.mimes'              => 'Campo "Imagem" aceita apenas as extensões .jpg, .jpeg e .png',
    ]);


    if ($validator->fails()) {
      return response()->json([
                  'error' => $validator->errors()->all()
              ]);
    }

    $preco_compra_formatado = str_replace('.', '', $request->preco_compra);
    $preco_venda_formatado  = str_replace('.', '', $request->preco_venda);
    $vuntrib_formatado      = str_replace('.', '', $request->vuntrib);
    $preco_minimo_formatado = str_replace('.', '', $request->preco_minimo);

    $produto->cod_barra    = $request->input('cod_barra');
    $produto->nome         = $request->input('nome');
    $produto->preco_compra = str_replace(',', '.', $preco_compra_formatado);
    $produto->preco_venda  = str_replace(',', '.', $preco_venda_formatado);
    $produto->vuntrib      = str_replace(',', '.', $vuntrib_formatado);
    $produto->preco_minimo = str_replace(',', '.', $preco_minimo_formatado);
    
    $produto->estoque      = $request->input('estoque');
    $produto->descricao    = $request->input('descricao');
    $produto->category_id  = $request->input('category_id');
    $produto->validade     = $request->input('validade');

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
    $produtos_count = Product::where('estoque', '<=', 5)->count();
    $estoque_baixo = false;

    return response()->json(['produtos_count' => $produtos_count, 'estoque_baixo' => $estoque_baixo]);
  }

}
