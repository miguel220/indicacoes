<?php

namespace App\Http\Controllers;

use App\Models\Indicacao;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IndicacaoController extends Controller
{
    // Listar todos
    public function index(): JsonResponse
    {
        $indicacoes = Indicacao::orderBy('criado_em', 'desc')->get();
        return response()->json($indicacoes);
    }

    // Adicionar
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'email'    => 'required|email|unique:indicacoes,email',
            'telefone' => 'required|regex:/^\(\d{2}\) \d{5}-\d{4}$/',
        ], [
            'email.unique' => 'Este e-mail já está sendo usado.'
        ]);

        try {
            $indicacao = Indicacao::create($request->only('nome', 'email', 'telefone'));

            return response()->json([
                'mensagem' => 'Indicação cadastrada com sucesso!',
                'indicacao' => $indicacao
            ], 201);

        } catch (QueryException $e) {

            return response()->json([
                'erro' => 'Erro ao salvar. Tente novamente.'
            ], 500);
        }
    }

    // Deletar
    public function destroy($id): JsonResponse
    {
        $indicacoes = Indicacao::findOrFail($id);
        $indicacoes->delete();

        return response()->json(['message' => 'Indicacao removido!']);
    }
}