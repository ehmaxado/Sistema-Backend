# Tarefas — Dev 2

**Responsabilidade:** Implementar o módulo **Serviços** em `app/Dev2/` (dentro do projeto Laravel em `Sistema/`).

> Esperar o **Dev 1 finalizar a Etapa 4** (Docker no ar + Laravel configurado + namespaces configurados) antes de começar.

---

## Pré-requisitos

**Docker + Docker Compose + Git** instalados. Nada de PHP/Composer/Postgres na máquina host.

> **Regra de ouro:** todos os comandos `php artisan` e `composer` rodam dentro do container via `docker compose exec backend <comando>`.

---

## Checklist

### Etapa 1 — Preparação

- [ ] Clonar e entrar na pasta correta:
  ```bash
  git clone <url-do-repo> ProjetoAgendamento
  cd ProjetoAgendamento/Sistema
  docker compose up -d --build
  docker compose exec app composer install
  docker compose exec app cp .env.example .env
  docker compose exec app php artisan key:generate
  docker compose exec app php artisan migrate
  ```

- [ ] Confirmar que o módulo Dev1 já está funcionando:
  ```bash
  curl http://localhost:8000/api/clientes
  # Deve retornar lista (mesmo que vazia)
  ```

- [ ] Criar branch: `git checkout -b dev2/servicos`

### Etapa 2 — Migration

- [x] Criar:
  ```bash
  docker compose exec app php artisan make:migration create_servicos_table
  ```

- [x] Editar `database/migrations/xxxx_create_servicos_table.php`:
  ```php
  public function up(): void
  {
      Schema::create('servicos', function (Blueprint $table) {
          $table->id();
          $table->string('nome', 150);
          $table->text('descricao')->nullable();
          $table->integer('duracao_minutos');
          $table->decimal('valor', 10, 2);
          $table->boolean('status')->default(true);
          $table->timestamps();
      });
  }

  public function down(): void
  {
      Schema::dropIfExists('servicos');
  }
  ```

- [ ] Rodar: `docker compose exec app php artisan migrate`

### Etapa 3 — Model (`app/Dev2/Models/Servico.php`)

- [x] Criado `app/Dev2/Models/Servico.php` conforme a especificação.

```php
<?php

namespace Dev2\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'servicos';
    protected $fillable = ['nome', 'descricao', 'duracao_minutos', 'valor', 'status'];
    protected $casts = [
        'status'          => 'boolean',
        'valor'           => 'decimal:2',
        'duracao_minutos' => 'integer',
    ];
}
```

### Etapa 4 — Request (`app/Dev2/Requests/ServicoRequest.php`)

- [x] Criado `app/Dev2/Requests/ServicoRequest.php` conforme a especificação.

```php
<?php

namespace Dev2\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nome'            => ['required', 'string', 'max:150'],
            'descricao'       => ['nullable', 'string'],
            'duracao_minutos' => ['required', 'integer', 'min:1'],
            'valor'           => ['required', 'numeric', 'min:0'],
            'status'          => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'       => 'O nome do serviço é obrigatório.',
            'duracao_minutos.min' => 'A duração deve ser maior que zero.',
            'valor.min'           => 'O valor não pode ser negativo.',
        ];
    }
}
```

### Etapa 5 — Controller (`app/Dev2/Controllers/ServicoController.php`)

- [x] Criado `app/Dev2/Controllers/ServicoController.php` conforme a especificação.

```php
<?php

namespace Dev2\Controllers;

use App\Http\Controllers\Controller;
use Dev2\Models\Servico;
use Dev2\Requests\ServicoRequest;

class ServicoController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Servico::orderBy('nome')->get()]);
    }

    public function store(ServicoRequest $request)
    {
        return response()->json(['data' => Servico::create($request->validated())], 201);
    }

    public function show(int $id)
    {
        return response()->json(['data' => Servico::findOrFail($id)]);
    }

    public function update(ServicoRequest $request, int $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->update($request->validated());
        return response()->json(['data' => $servico]);
    }

    public function destroy(int $id)
    {
        Servico::findOrFail($id)->delete();
        return response()->json(['data' => ['id' => $id]]);
    }

    public function toggleStatus(int $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->status = !$servico->status;
        $servico->save();
        return response()->json(['data' => ['id' => $servico->id, 'status' => $servico->status]]);
    }
}
```

### Etapa 6 — Rotas (`routes/api.php`)

Adicionar **abaixo** das rotas do Dev 1:

```php
use Dev2\Controllers\ServicoController;

// IMPORTANTE: toggleStatus deve vir ANTES do apiResource
Route::patch('servicos/{id}/status', [ServicoController::class, 'toggleStatus']);
Route::apiResource('servicos', ServicoController::class);
```

- [x] Rotas de `servicos` adicionadas em `routes/api.php`.
- [ ] Verificar: `docker compose exec app php artisan route:list --path=api/servicos`

### Etapa 7 — Testes manuais (Postman / Insomnia)

- [ ] `POST http://localhost:8000/api/servicos` → 201 com `status: true`
  ```json
  { "nome": "Consulta nutricional", "descricao": "Avaliação completa", "duracao_minutos": 60, "valor": 150.00 }
  ```
- [ ] `GET  http://localhost:8000/api/servicos` → 200
- [ ] `GET  http://localhost:8000/api/servicos/1` → 200
- [ ] `PUT  http://localhost:8000/api/servicos/1` → 200
- [ ] `PATCH http://localhost:8000/api/servicos/1/status` → 200, status invertido
- [ ] `DELETE http://localhost:8000/api/servicos/2` → 200
- [ ] `POST` sem `nome` → 422
- [ ] `GET  /api/servicos/9999` → 404

### Etapa 8 — Seeder de serviços

- [x] Criar: `docker compose exec app php artisan make:seeder ServicoSeeder`

- [x] `database/seeders/ServicoSeeder.php`:
  ```php
  use Dev2\Models\Servico;

  public function run(): void
  {
      Servico::create(['nome' => 'Consulta nutricional', 'descricao' => 'Avaliação completa', 'duracao_minutos' => 60, 'valor' => 150.00, 'status' => true]);
      Servico::create(['nome' => 'Reavaliação', 'descricao' => 'Acompanhamento', 'duracao_minutos' => 30, 'valor' => 80.00, 'status' => true]);
      Servico::create(['nome' => 'Serviço descontinuado', 'descricao' => null, 'duracao_minutos' => 45, 'valor' => 100.00, 'status' => false]);
  }
  ```

- [x] Registrar em `DatabaseSeeder.php`.
- [ ] Rodar: `docker compose exec app php artisan db:seed`

### Etapa 9 — Commit

- [ ] `git add . && git commit -m "feat: módulo serviços (Dev2)"`
- [ ] `git push origin dev2/servicos`
- [ ] Avisar Dev 3 que pode começar.

---

## O que falta testar

### Validação no container

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan route:list --path=api/servicos
docker compose exec app php artisan db:seed
```

### Testes da API

```bash
curl -i -X POST http://localhost:8000/api/servicos \
  -H 'Content-Type: application/json' \
  -d '{"nome":"Consulta nutricional","descricao":"Avaliação completa","duracao_minutos":60,"valor":150.00}'

curl -i http://localhost:8000/api/servicos
curl -i http://localhost:8000/api/servicos/1
curl -i -X PUT http://localhost:8000/api/servicos/1 \
  -H 'Content-Type: application/json' \
  -d '{"nome":"Consulta atualizada","descricao":"Texto","duracao_minutos":45,"valor":120.00}'
curl -i -X PATCH http://localhost:8000/api/servicos/1/status
curl -i -X DELETE http://localhost:8000/api/servicos/2
curl -i -X POST http://localhost:8000/api/servicos \
  -H 'Content-Type: application/json' \
  -d '{"descricao":"Sem nome","duracao_minutos":30,"valor":50.00}'
curl -i http://localhost:8000/api/servicos/9999
```

---

## Resumo dos arquivos que o Dev 2 cria/edita

```
Sistema/
├── database/migrations/xxxx_create_servicos_table.php
├── database/seeders/ServicoSeeder.php
├── database/seeders/DatabaseSeeder.php     # registrar o seeder
├── routes/api.php                          # adicionar rotas de serviços
└── app/
    └── Dev2/
        ├── Models/Servico.php
        ├── Requests/ServicoRequest.php
        └── Controllers/ServicoController.php
```

---

## Nota para o Dev 3

O campo `status` do model `Servico` é usado pelo Dev 3 para bloquear agendamentos em serviços inativos. Certifique-se de que `status` está em `$fillable` e em `$casts` como `boolean`.
