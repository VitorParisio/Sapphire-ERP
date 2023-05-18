<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Emitente;

class EmitenteController extends Controller
{
    public function index()
    {
        return view('emitentes.index');
    }

    public function store(Request $request)
    {
       
        $data      = $request->all();
        $validator = Validator::make($data, [
            'cnpj'              => 'required|regex:/^[0-9]+$/|max:14|min:11|unique:emitentes,cnpj',
            'razao_social'      => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
            'nome_fantasia'     => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
            'ie'                => 'required|numeric|unique:emitentes,ie',
            'im'                => 'nullable|numeric',
            'cnae'              => 'nullable|numeric',
            'cep'               => 'required|regex:/^[0-9]+$/|max:8',
            'rua'               => 'required|regex:/^[a-z A-Z 0-9]*$/',
            'numero'            => 'required|numeric',
            'complemento'       => 'nullable|regex:/^[a-z A-Z 0-9 "-]*$/',
            'bairro'            => 'required|regex:/^[a-z A-Z "-]+$/',
            'cidade'            => 'required|regex:/^[a-z A-Z]+$/',
            'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
            'cuf'               => 'nullable|numeric',
            'cibge'             => 'nullable|numeric',
            'telefone'          => 'nullable|numeric',
            'certificado_a1'    => 'required|mimetypes:application/octet-stream',
            'senha_certificado' => 'required',
        ],
        [
            'cnpj.required'             => 'Campo "CNPJ/CPF" deve ser preenchido.',
            'cnpj.regex'                => 'Digitar apenas números no campo "CNPJ/CPF".',
            'cnpj.max'                  => 'Excedeu o limite de digitos no campo "CNPJ/CPF".',
            'cnpj.min'                  => 'Poucos dígitos no campo "CNPJ/CPF".',
            'cnpj.unique'               => 'CNPJ já cadastrado.',
            'cnpj.unique'               => 'Insc. Estadual já cadastrada.',
            'razao_social.required'     => 'Campo "Razão Social" deve ser preenchido.',
            'razao_social.regex'        => 'Digite apenas letras e/ou números no campo "Razão Social".',
            'razao_social.min'          => 'Poucos dígitos no campo "Razão Social".',
            'nome_fantasia.required'    => 'Campo "Fantasia" deve ser preenchido.',
            'nome_fantasia.regex'       => 'Digite apenas letras e/ou números no campo "Fantasia".',
            'nome_fantasia.min'         => 'Poucos dígitos no campo "Fantasia".',
            'ie.required'               => 'Campo "Inscrição Estadual" deve ser preenchido.',
            'ie.numeric'                => 'Digite apenas números no campo "Inscrição Estadual".',
            'im.numeric'                => 'Digite apenas números no campo "Inscrição Municipal".',
            'cnae.numeric'              => 'Digite apenas números no campo "CNAE".',
            'cep.regex'                 => 'Digite apenas números no campo "Cep".',
            'cep.required'              => 'Campo "Cep" deve ser preenchido.',
            'cep.max'                   => 'Excedeu o limite de digitos no campo "Cep".',
            'rua.required'              => 'Campo "Logradouro" deve ser preenchido.',
            'rua.regex'                 => 'Digite apenas letras e/ou números no campo "Logradouro".',
            'complemento.regex'         => 'Digite apenas letras e/ou números no campo "Complemento".',
            'numero.required'           => 'Campo "Número" deve ser preenchido.',
            'numero.numeric'            => 'Digite apenas números no campo "Número".',
            'bairro.required'           => 'Campo "Bairro" deve ser preenchido.',
            'bairro.regex'              => 'Digitar apenas letras no campo "Bairro".',
            'cidade.required'           => 'Campo "Cidade" deve ser preenchido.',
            'cidade.regex'              => 'Digitar apenas letras no campo "Cidade".',
            'uf.required'               => 'Campo "UF" deve ser preenchido.',
            'uf.regex'                  => 'Digitar apenas a sigla do estado no campo "UF".',
            'cuf.required'              => 'Campo "cUF" deve ser preenchido.',
            'cuf.numeric'               => 'Digitar apenas números no campo "cUF".',
            'cuf.max'                   => 'Máximo de 2 dígitos no campo "cUF',
            'cibge.required'            => 'Campo "cIBGE" deve ser preenchido.',
            'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
            'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE',
            'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
            'certificado_a1.required'   => 'Campo "Certificado Digital" deve ser preenchido.',
            'certificado_a1.mimetypes'  => 'Campo "Certificado Digital" aceita as extensões .pfx e .p12',
            'senha_certificado.required'=> 'Campo "Senha (certificado)" deve ser preenchido.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        
        if ($request->certificado_a1 != null)
        {
            if ($request->certificado_a1->isValid())
            {
                $certificado_path = Str::of($request->razao_social)->slug('-'). '.' .$request->certificado_a1->getClientOriginalExtension();

                $certificado = $request->certificado_a1->storeAs('certificados', $certificado_path);
                $data['certificado_a1'] = $certificado;
            }
        }

        Emitente::create($data);

        return response()->json(['message' => 'Empresa cadastrada com sucesso.']);
    }

    function getEmpresa(Request $request){
        $query              = $request->get('query');
        $output             = '';
        $total_row          = '';
    
        if ($request->ajax())
        {
          if ($query != '')
          {
            $empresas = Emitente::where('razao_social','LIKE','%'.$query.'%')
            ->get(); 
          }
          else
          {
            $empresas = Emitente::orderBy('id', 'ASC')->get();
          }
    
          $total_row   = $empresas->count();
          $total_empresas = Emitente::all()->count();
    
          if ($total_row > 0)
          {
            foreach($empresas as $row)
            {
              $im = $row->im == null ? "<i>Não definido</i>" : $row->im;
              $cnae = $row->cnae == null ? "<i>Não definido</i>" : $row->cnae;
              $complemento = $row->complemento == null ? "<i>Não definido</i>" : $row->complemento;

              $output .='
                <tr>
                  <td>'.$row->id.'</td>
                  <td>'.$row->cnpj.'</td>
                  <td>'.ucfirst($row->razao_social).'</td>
                  <td>'.$row->ie.'</td>
                  <td>'.$row->cidade.'</td>
                  <td style="display:none;">'.ucfirst($row->nome_fantasia).'</td>
                  <td style="display:none;">'.$im.'</td>
                  <td style="display:none;">'.$cnae.'</td>
                  <td style="display:none;">'.$row->cep.'</td>
                  <td style="display:none;">'.$row->rua.'</td>
                  <td style="display:none;">'.$row->numero.'</td>
                  <td style="display:none;">'.$complemento.'</td>
                  <td style="display:none;">'.$row->bairro.'</td>
                  <td style="display:none;">'.$row->uf.'</td>
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
                <td colspan="7" style="font-weight:100; font-size: 19px"><i>Empresa não encontrada.</i></td>
              </tr>
            ';
          }
    
          $data = array(
            'output'            => $output,
            'total_empresas'     => $total_empresas,
          );
    
          return response()->json($data);
        }
      }
    
}
