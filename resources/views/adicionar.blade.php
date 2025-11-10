<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Indicacoes</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen p-4">

<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h1 class="text-3xl font-bold text-center text-indigo-700 mb-10">Gerenciador de Indicacoes</h1>

        <!-- Formulário -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-xl mb-8 border border-indigo-200">
            <h2 class="text-xl font-semibold text-indigo-900 mb-5">Adicionar Indicacao</h2>
            <div class="grid md:grid-cols-3 gap-4 mb-5">
                <input type="text" id="nome" placeholder="Nome completo" class="input-field">
                <input type="email" id="email" placeholder="email@exemplo.com" class="input-field">
                <input type="text" id="telefone" placeholder="(11) 98765-4321" class="input-field" oninput="formatarTelefone(this)">
            </div>
            <button id="btn-adicionar" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                Adicionar Indicacao
            </button>
        </div>

        <!-- Lista -->
        <div id="lista-Indicacoes" class="space-y-4">
            <div class="text-center py-10">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                <p class="text-gray-600 mt-2">Carregando Indicacoes...</p>
            </div>
        </div>
    </div>
</div>

<style>
    .input-field {
        @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-shadow duration-200;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .input-field:focus {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
    }
</style>

<script>
    // Configuração do CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const API = '/api/indicacao';

    // Máscara de telefone
    function formatarTelefone(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 11);
        if (v.length <= 2) {
            input.value = v ? `(${v}` : '';
        } else if (v.length <= 7) {
            input.value = `(${v.slice(0,2)}) ${v.slice(2)}`;
        } else {
            input.value = `(${v.slice(0,2)}) ${v.slice(2,7)}-${v.slice(7)}`;
        }
    }

    // Carregar Indicacoes
    function carregarIndicacao() {
        $.get(API)
            .done(function(data) {
                renderizarLista(data);
            })
            .fail(function() {
                $('#lista-Indicacoes').html('<p class="text-red-600 text-center">Erro ao carregar Indicacoes.</p>');
            });
    }

    // Renderizar lista
    function renderizarLista(Indicacoes) {
        if (Indicacoes.length === 0) {
            $('#lista-Indicacoes').html('<p class="text-center text-gray-500 italic py-10">Nenhum Indicacao cadastrado ainda.</p>');
            return;
        }

        const html = Indicacoes.map(c => `
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gradient-to-r from-gray-50 to-gray-100 p-5 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex-1">
                    <div class="font-bold text-lg text-gray-800">${c.nome}</div>
                    <div class="text-sm text-gray-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        ${c.email}
                    </div>
                    <div class="text-sm font-medium text-indigo-600 flex items-center gap-1 mt-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        ${c.telefone}
                    </div>
                </div>
                <button onclick="remover(${c.id})" class="mt-3 sm:mt-0 bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition transform hover:scale-105">
                    Remover
                </button>
            </div>
        `).join('');

        $('#lista-Indicacoes').html(html);
    }

    // Adicionar
    $('#btn-adicionar').on('click', function() {
        const nome = $('#nome').val().trim();
        const email = $('#email').val().trim();
        const telefone = $('#telefone').val().trim();

        if (!nome || !email || !telefone) {
            alert('Preencha todos os campos!');
            return;
        }
        if (!/^\(\d{2}\) \d{5}-\d{4}$/.test(telefone)) {
            alert('Telefone inválido! Use: (11) 98765-4321');
            return;
        }

        $(this).prop('disabled', true).text('Adicionando...');

        $.post(API, { nome, email, telefone })
            .done(function() {
                limparCampos();
                carregarIndicacao();
                alert('Indicacao adicionado com sucesso!');
            })
            .fail(function(xhr) {
                const erro = xhr.responseJSON?.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join(', ')
                    : 'Erro ao salvar. Tente novamente.';
                alert(erro);
            })
            .always(function() {
                $('#btn-adicionar').prop('disabled', false).text('Adicionar Indicacao');
            });
    });

    // Remover
    function remover(id) {
        if (!confirm('Tem certeza que deseja remover esta Indicacao?')) return;

        $.ajax({
            url: `${API}/${id}`,
            type: 'DELETE',
            success: function() {
                carregarIndicacao();
            },
            error: function() {
                alert('Erro ao remover.');
            }
        });
    }

    function limparCampos() {
        $('#nome, #email, #telefone').val('');
    }

    // Inicializar
    $(document).ready(function() {
        carregarIndicacao();
    });
</script>

</body>
</html>