<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Destinatario;

class DestinatarioController extends Controller
{
    public function index()
    {
        return view('destinatarios.index');
    }

    public function store(Request $request)
    {
       
        $data      = $request->all();
        $validator = Validator::make($data, [
            'nome'              => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
            'cpf_cnpj'          =>'required|regex:/^[0-9]+$/|min:7',
            'rg_ie'             => 'required|numeric',
            'cep'               => 'required|regex:/^[0-9]+$/|max:8',
            'rua'               => 'required|regex:/^[a-z A-Z 0-9]*$/',
            'numero'            => 'required|numeric',
            'complemento'       => 'nullable',
            'bairro'            => 'required|regex:/^[a-z A-Z "-]+$/',
            'cidade'            => 'required|regex:/^[a-z A-Z]+$/',
            'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
            'cuf'               => 'nullable|numeric',
            'cibge'             => 'nullable|numeric',
            'telefone'          => 'nullable|numeric',
            'email'             => 'nullable|email|unique:destinatarios,email', 
        ],
        // [
        //     'cnpj.required'             => 'Campo "CNPJ/CPF" deve ser preenchido.',
        //     'cnpj.regex'                => 'Digitar apenas números no campo "CNPJ/CPF".',
        //     'cnpj.max'                  => 'Excedeu o limite de digitos no campo "CNPJ/CPF".',
        //     'cnpj.min'                  => 'Poucos dígitos no campo "CNPJ/CPF".',
        //     'razao_social.required'     => 'Campo "Razão Social" deve ser preenchido.',
        //     'razao_social.regex'        => 'Digite apenas letras e/ou números no campo "Razão Social".',
        //     'razao_social.min'          => 'Poucos dígitos no campo "Razão Social".',
        //     'nome_fantasia.required'    => 'Campo "Fantasia" deve ser preenchido.',
        //     'nome_fantasia.regex'       => 'Digite apenas letras e/ou números no campo "Fantasia".',
        //     'nome_fantasia.min'         => 'Poucos dígitos no campo "Fantasia".',
        //     'rg_ie.required'            => 'Campo "Inscrição Estadual" deve ser preenchido.',
        //     'rg_ie.numeric'             => 'Digite apenas números no campo "Inscrição Estadual".',
        //     'im.numeric'                => 'Digite apenas números no campo "Inscrição Municipal".',
        //     'cnae.numeric'              => 'Digite apenas números no campo "CNAE".',
        //     'cep.regex'                 => 'Digite apenas números no campo "Cep".',
        //     'cep.required'              => 'Campo "Cep" deve ser preenchido.',
        //     'cep.max'                   => 'Excedeu o limite de digitos no campo "Cep".',
        //     'rua.required'              => 'Campo "Logradouro" deve ser preenchido.',
        //     'rua.regex'                 => 'Digite apenas letras e/ou números no campo "Logradouro".',
        //     'numero.required'           => 'Campo "Número" deve ser preenchido.',
        //     'numero.numeric'            => 'Digite apenas números no campo "Número".',
        //     'bairro.required'           => 'Campo "Bairro" deve ser preenchido.',
        //     'bairro.regex'              => 'Digitar apenas letras no campo "Bairro".',
        //     'cidade.required'           => 'Campo "Cidade" deve ser preenchido.',
        //     'cidade.regex'              => 'Digitar apenas letras no campo "Cidade".',
        //     'uf.required'               => 'Campo "UF" deve ser preenchido.',
        //     'uf.regex'                  => 'Digitar apenas a sigla do estado no campo "UF".',
        //     'cuf.required'              => 'Campo "cUF" deve ser preenchido.',
        //     'cuf.numeric'               => 'Digitar apenas números no campo "cUF".',
        //     'cuf.max'                   => 'Máximo de 2 dígitos no campo "cUF',
        //     'cibge.required'            => 'Campo "cIBGE" deve ser preenchido.',
        //     'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
        //     'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE',
        //     'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
        //     'certificado_a1.required'   => 'Campo "Certificado Digital" deve ser preenchido.',
        //     'certificado_a1.mimetypes'  => 'Campo "Certificado Digital" aceita as extensões .pfx e .p12',
        //     'senha_certificado.required'=> 'Campo "Senha (certificado)" deve ser preenchido.'
        // ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        
        Destinatario::create($data);

        return response()->json(['message' => 'Cliente cadastrado(a) com sucesso.']);
    }
}
