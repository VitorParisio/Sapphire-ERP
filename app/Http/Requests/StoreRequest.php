<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cod_barra'    => 'numeric|unique:products,cod_barra,'.$this->id,
            'nome'         => 'required|string|min:2|max:100|unique:products,nome,'.$this->id,
            'preco_compra' => 'required',
            'preco_venda'  => 'required',
            'estoque'      => 'required|numeric',
            'validade'     => 'date|nullable',
            'img'          => 'image|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'cod_barra.numeric'    => 'Digite apenas números no campo "Cód. barra".',
            'cod_barra.unique'     => 'Código de barra já cadastrado.',
            'nome.unique'          => 'Produto já cadastrado.',
            'nome.required'        => 'Preencha o campo "Produto".',
            'nome.max'             => 'Excedeu o limite de 100 caracteres.',
            'nome.min'             => 'Palavra com mínimo de 2 caracteres.',
            'preco_compra.required'=> 'Preencha o campo "Preço custo.".',
            'preco_venda.required' => 'Preencha o campo "Preço venda.".',
            'estoque.required'     => 'Preencha o campo "Estoque.".',
            'estoque.numeric'      => 'Digite apenas números no campo "Estoque".',
            'validade.date'        => 'Data inválida.',
            'img.max'              => 'Tamanho máximo de 2MB (2048KB).'
        ];
    }
}
