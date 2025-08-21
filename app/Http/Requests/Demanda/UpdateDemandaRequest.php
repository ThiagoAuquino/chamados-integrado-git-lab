<?php

namespace App\Http\Requests\Demanda;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDemandaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // alterar se necessário
    }

    public function rules(): array
    {
        return [
            'produto'         => 'sometimes|string|max:255',
            'chamado'         => 'nullable|string|max:255',
            'descricao'       => 'sometimes|string',
            'tipo'            => 'sometimes|in:bug,melhoria,funcionalidade',
            'data_previsao'   => 'sometimes|date|after_or_equal:today',
            'cliente'         => 'sometimes|string|max:255',
            'responsavel_id'  => 'nullable|integer|exists:users,id',
            'status'          => 'sometimes|in:em_branco,analise,em_execucao,em_testes,validacao,entregue',
            'prioridade'      => 'sometimes|in:verde,amarelo,laranja,vermelho',
        ];
    }
}
