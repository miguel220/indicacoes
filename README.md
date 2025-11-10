# **Gerenciador de Contatos (Indicações)**  
*Um CRUD simples com Laravel + Blade + Tailwind CSS + AJAX (jQuery)*

---

## Visão Geral

Este é um **sistema de cadastro de indicações (contatos)** com:

- Formulário para adicionar **Nome, E-mail e Telefone (com máscara)**
- Lista dinâmica com **remoção**
- Dados salvos no **banco de dados MySQL**
- Comunicação com **API REST (Laravel)**
- Interface moderna com **Tailwind CSS**
- Requisições **AJAX (jQuery)**
- Validação no backend e frontend

---

## Tecnologias Utilizadas

| Tecnologia        | Versão / Uso |
|-------------------|-------------|
| **Laravel**       | 10.x ou superior |
| **PHP**           | >= 8.1 |
| **MySQL**         | 5.7+ |
| **Tailwind CSS**  | CDN (ou via Vite) |
| **jQuery**        | 3.7.1 (AJAX) |
| **Blade**         | Templates Laravel |
| **Axios**         | Não usado (substituído por jQuery) |

---

## Estrutura do Projeto

```
app/
├── Models/
│   └── Indicacao.php
├── Http/
│   └── Controllers/
│       └── IndicacaoController.php
database/
├── migrations/
│   └── 2025_xx_xx_create_indicacoes_table.php
resources/
├── views/
│   └── indicacoes.blade.php
├── css/
│   └── app.css (Tailwind)
routes/
├── web.php
└── api.php
```

---

## Banco de Dados

### Tabela: `indicacoes`

| Coluna         | Tipo             | Descrição                     |
|----------------|------------------|-------------------------------|
| `id`           | bigint           | Chave primária                |
| `nome`         | varchar(255)     | Nome completo                 |
| `email`        | varchar(255)     | E-mail (único)                |
| `telefone`     | varchar(255)     | Formato: `(11) 98765-4321`    |
| `criado_em`    | timestamp        | Data de criação               |
| `atualizado_em`| timestamp        | Data de atualização           |

> **Observação**: Usamos `criado_em` e `atualizado_em` (em português), não `created_at`.

---

## Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/gerenciador-indicacoes.git
cd gerenciador-indicacoes
```

### 2. Instale as dependências

```bash
composer install
npm install && npm run build
```

### 3. Configure o `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=indicacoes
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Execute as migrations

```bash
php artisan migrate
```

### 6. Inicie o servidor

```bash
php artisan serve
```

Acesse: [http://localhost:8000/](http://localhost:8000/)

---

## Rotas

### Web (Blade)

| Rota             | Método | Descrição               |
|------------------|--------|-------------------------|
| `/indicacoes`    | GET    | Página principal (Blade) |

### API (JSON)

| Rota                  | Método | Ação                     |
|-----------------------|--------|--------------------------|
| `GET /api/indicacao` | GET    | Listar todos             |
| `POST /api/indicacao`| POST   | Adicionar novo           |
| `DELETE /api/indicacao/{id}` | DELETE | Remover |

---

## Funcionalidades

| Funcionalidade         | Status |
|------------------------|--------|
| Adicionar contato      | Done |
| Listar contatos        | Done |
| Remover contato        | Done |
| Máscara de telefone    | Done |
| Validação (frontend + backend) | Done |
| Feedback visual (AJAX) | Done |
| Dados persistidos      | Done |
| Responsivo (mobile)    | Done |

---

## Model: `Indicacao.php`

```php
protected $table = 'indicacoes';
protected $fillable = ['nome', 'email', 'telefone'];

const CREATED_AT = 'criado_em';
const UPDATED_AT = 'atualizado_em';
```

---

## Controller: `IndicacaoController.php`

```php
public function index()
{
    return response()->json(Indicacao::orderBy('criado_em', 'desc')->get());
}

public function store(Request $request)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|unique:indicacoes,email',
        'telefone' => 'required|regex:/^\(\d{2}\) \d{5}-\d{4}$/',
    ]);

    $indicacao = Indicacao::create($request->only('nome', 'email', 'telefone'));

    return response()->json($indicacao, 201);
}

public function destroy($id)
{
    Indicacao::findOrFail($id)->delete();
    return response()->json(['message' => 'Removido com sucesso']);
}
```

---

## Interface (Blade + Tailwind + jQuery)

- Formulário com validação
- Lista com cards responsivos
- Botão de remoção com confirmação
- Feedback de carregamento
- Máscara automática de telefone

---

## Comandos Úteis

```bash
# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Ver rotas
php artisan route:list

# Testar no Tinker
php artisan tinker
>>> App\Models\Indicacao::all();
```

---

## Melhorias Futuras

- [ ] Editar contato (PUT)
- [ ] Busca em tempo real
- [ ] Paginação
- [ ] Exportar para Excel/CSV
- [ ] Autenticação (Sanctum)
- [ ] Livewire (opcional)

---

## Autor

**Desenvolvido por:** [Miguel Borges]  
**Data:** 10 de Novembro de 2025  


---

> **"Um sistema simples, funcional e bonito."**  
> Feito com Laravel, amor e café.




