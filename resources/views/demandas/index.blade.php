@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Demandas</h2>

    <table class="table table-bordered table-striped" id="demandas-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Cliente</th>
                <th>Previsão</th>
                <th>Responsável</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="sortable-demandas">
            @foreach ($demandas as $demanda)
                <tr data-id="{{ $demanda->id }}" class="demanda-row {{ 'prioridade-' . $demanda->priority }}">
                    <td>{{ $demanda->produto }}</td>
                    <td>{{ $demanda->cliente }}</td>
                    <td>{{ \Carbon\Carbon::parse($demanda->data_previsao)->format('d/m/Y') }}</td>
                    <td>{{ optional($demanda->responsavel)->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($demanda->status->nome) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('styles')
<style>
    /* Cores visuais conforme prioridade */
    .prioridade-verde { background-color: #d4edda !important; }     /* Verde claro */
    .prioridade-amarela { background-color: #fff3cd !important; }   /* Amarelo claro */
    .prioridade-laranja { background-color: #ffe5b4 !important; }   /* Laranja claro */
    .prioridade-vermelha { background-color: #f8d7da !important; }  /* Vermelho claro */
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let tabela = document.getElementById('sortable-demandas');

        new Sortable(tabela, {
            animation: 150,
            onEnd: function () {
                let ordemAtual = [];
                tabela.querySelectorAll('tr').forEach((row, index) => {
                    ordemAtual.push({
                        id: row.getAttribute('data-id'),
                        order: index + 1
                    });
                });

                fetch('{{ route('demandas.reordenar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ demandas: ordemAtual })
                })
                .then(response => response.json())
                .then(data => {
                    // Atualiza classes visuais (prioridade) com base na ordem
                    tabela.querySelectorAll('tr').forEach((row, index) => {
                        // Remove classes antigas
                        row.classList.remove('prioridade-verde', 'prioridade-amarela', 'prioridade-laranja', 'prioridade-vermelha');

                        // Adiciona nova prioridade com base na nova posição
                        let prioridade;
                        if (index === 0) prioridade = 'vermelha';
                        else if (index === 1) prioridade = 'amarela';
                        else if (index === 2) prioridade = 'laranja';
                        else prioridade = 'verde';

                        row.classList.add('prioridade-' + prioridade);
                    });
                })
                .catch(err => {
                    alert('Erro ao atualizar ordem das demandas.');
                    console.error(err);
                });
            }
        });
    });
</script>
@endpush
