@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Histórico da Demanda #{{ $demanda_id }}</h2>

    @if ($logs && count($logs) > 0)
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Campo Alterado</th>
                    <th>De</th>
                    <th>Para</th>
                    <th>Descrição</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log['user_id'] }}</td>
                        <td>{{ $log['action'] }}</td>
                        <td>{{ $log['field_changed'] ?? '-' }}</td>
                        <td>{{ $log['old_value'] ?? '-' }}</td>
                        <td>{{ $log['new_value'] ?? '-' }}</td>
                        <td>{{ $log['description'] ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log['created_at'])->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info mt-3">
            Nenhum log encontrado para esta demanda.
        </div>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
