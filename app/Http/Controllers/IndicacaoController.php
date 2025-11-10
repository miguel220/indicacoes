<?php

namespace App\Http\Controllers;

use App\Models\Indicacao;
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
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:indicacao,email',
            'telefone' => 'required|string|regex:/^\(\d{2}\) \d{5}-\d{4}$/',
        ]);

        $indicacoes = Indicacao::create($request->only('nome', 'email', 'telefone'));

        return response()->json([
            'message' => 'Indicacao adicionado com sucesso!',
            'indicacao' => $indicacoes
        ], 201);
    }

    // Deletar
    public function destroy($id): JsonResponse
    {
        $indicacoes = Indicacao::findOrFail($id);
        $indicacoes->delete();

        return response()->json(['message' => 'Indicacao removido!']);
    }
}