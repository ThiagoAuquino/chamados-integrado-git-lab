<?php

namespace App\Http\Requests\Demanda;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // alterar se houver autorização específica
    }

    public function rules(): array
    {
        return [
            'produto'         => 'required|string|max:255',
            'chamado'         => 'nullable|string|max:255',
            'descricao'       => 'required|string',
            'tipo'            => 'required|in:bug,melhoria,funcionalidade',
            'data_previsao'   => 'required|date|after_or_equal:today',
            'cliente'         => 'required|string|max:255',
            'responsavel_id'  => 'nullable|integer|exists:users,id',
            'status'          => 'required|in:em_branco,analise,em_execucao,em_testes,validacao,entregue',
            'prioridade'      => 'required|in:verde,amarelo,laranja,vermelho',
        ];
    }
}
