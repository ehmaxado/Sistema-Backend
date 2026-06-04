# Sistema de Agendamento de Atendimentos

API REST desenvolvida em Laravel + PostgreSQL para gerenciar clientes, serviços e agendamentos de atendimentos.

---

## Requisitos

- Docker
- Docker Compose

Não é necessário ter PHP, Composer ou PostgreSQL instalados na máquina.

---

## Como rodar o projeto

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd Sistema-Backend
```

### 2. Configure o ambiente

```bash
cp .env.example .env
```

O `.env.example` já vem com as credenciais corretas para o banco do Docker. Nenhuma alteração é necessária para rodar localmente.

### 3. Suba os containers

```bash
docker compose up -d --build
```

Aguarde o container do PostgreSQL passar pelo healthcheck (alguns segundos).

### 4. Instale as dependências

```bash
docker compose exec app composer install
```

### 5. Gere a chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

### 6. Rode as migrations

```bash
docker compose exec app php artisan migrate
```

### 7. (Opcional) Popule o banco com dados de exemplo

```bash
docker compose exec app php artisan db:seed
```

Isso cria clientes, serviços e agendamentos de exemplo para testes.

### 8. Verifique se está no ar

```bash
curl http://localhost:8000/api/clientes
```

Deve retornar `{"data":[]}` ou a lista de clientes caso tenha rodado o seed.

---

## Rotas disponíveis

A URL base da API é `http://localhost:8000/api`.

### Clientes

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/clientes` | Lista todos os clientes |
| POST | `/clientes` | Cria um cliente |
| GET | `/clientes/{id}` | Busca um cliente pelo ID |
| PUT | `/clientes/{id}` | Atualiza um cliente |
| DELETE | `/clientes/{id}` | Remove um cliente |

### Serviços

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/servicos` | Lista todos os serviços |
| POST | `/servicos` | Cria um serviço |
| GET | `/servicos/{id}` | Busca um serviço pelo ID |
| PUT | `/servicos/{id}` | Atualiza um serviço |
| DELETE | `/servicos/{id}` | Remove um serviço |
| PATCH | `/servicos/{id}/status` | Ativa ou desativa um serviço |

### Agendamentos

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/agendamentos` | Lista agendamentos (aceita filtros) |
| POST | `/agendamentos` | Cria um agendamento |
| GET | `/agendamentos/{id}` | Busca um agendamento pelo ID |
| PATCH | `/agendamentos/{id}/status` | Atualiza o status do agendamento |
| DELETE | `/agendamentos/{id}` | Cancela um agendamento |

**Filtros disponíveis no `GET /agendamentos`:**

```
GET /api/agendamentos?data=2025-06-10
GET /api/agendamentos?status=agendado
GET /api/agendamentos?data=2025-06-10&status=agendado
```

---

## Como testar no Postman

### Configuração inicial

1. Abra o Postman e crie uma nova **Collection** chamada `Agendamento API`.
2. Nas configurações da collection, adicione uma variável:
   - **Variable:** `base_url`
   - **Initial value:** `http://localhost:8000/api`
3. Em todas as requisições, use `{{base_url}}` no lugar da URL base.
4. Em cada requisição com body, defina o header:
   - `Content-Type: application/json`
   - `Accept: application/json`

---

### Clientes

**Criar cliente**
```
POST {{base_url}}/clientes

{
    "nome": "Maria Silva",
    "email": "maria@email.com",
    "telefone": "11999990000",
    "observacao": "Cliente preferencial"
}
```

**Listar clientes**
```
GET {{base_url}}/clientes
```

**Buscar cliente por ID**
```
GET {{base_url}}/clientes/1
```

**Atualizar cliente**
```
PUT {{base_url}}/clientes/1

{
    "nome": "Maria Silva Santos",
    "email": "maria@email.com",
    "telefone": "11999990001"
}
```

**Deletar cliente**
```
DELETE {{base_url}}/clientes/1
```

---

### Serviços

**Criar serviço**
```
POST {{base_url}}/servicos

{
    "nome": "Consulta nutricional",
    "descricao": "Avaliação completa",
    "duracao_minutos": 60,
    "valor": 150.00
}
```

**Listar serviços**
```
GET {{base_url}}/servicos
```

**Atualizar serviço**
```
PUT {{base_url}}/servicos/1

{
    "nome": "Consulta nutricional",
    "descricao": "Avaliação completa atualizada",
    "duracao_minutos": 45,
    "valor": 120.00
}
```

**Ativar/desativar serviço**
```
PATCH {{base_url}}/servicos/1/status
```
> Sem body. Cada chamada inverte o status atual.

**Deletar serviço**
```
DELETE {{base_url}}/servicos/1
```

---

### Agendamentos

**Criar agendamento**
```
POST {{base_url}}/agendamentos

{
    "cliente_id": 1,
    "servico_id": 1,
    "data": "2025-08-10",
    "hora": "09:00",
    "observacao": "Primeira consulta"
}
```

**Listar agendamentos**
```
GET {{base_url}}/agendamentos
```

**Filtrar por data**
```
GET {{base_url}}/agendamentos?data=2025-08-10
```

**Filtrar por status**
```
GET {{base_url}}/agendamentos?status=agendado
```

**Buscar por ID**
```
GET {{base_url}}/agendamentos/1
```

**Atualizar status**
```
PATCH {{base_url}}/agendamentos/1/status

{
    "status": "realizado"
}
```
> Valores aceitos: `agendado`, `realizado`, `cancelado`.

**Cancelar agendamento**
```
DELETE {{base_url}}/agendamentos/1
```
> O agendamento não é deletado do banco — o status é alterado para `cancelado`.

---

### Respostas de erro comuns

| Código | Situação |
|--------|----------|
| 422 | Dados inválidos (campo obrigatório ausente, formato errado, etc.) |
| 404 | Registro não encontrado |
| 409 / 422 | Conflito de horário ou serviço inativo |

---

## Como integrar com um frontend

### CORS

O Laravel já inclui o middleware de CORS. Para liberar o acesso do frontend, edite o arquivo `config/cors.php`:

```php
'allowed_origins' => ['http://localhost:3000'], // URL do seu frontend
```

Para liberar qualquer origem durante o desenvolvimento:

```php
'allowed_origins' => ['*'],
```

---

### URL base

Configure a URL da API em uma variável de ambiente do frontend. Exemplo com Vite (React ou Vue):

```env
VITE_API_URL=http://localhost:8000/api
```

---

### Exemplos com fetch (JavaScript puro)

**Listar agendamentos**
```js
const response = await fetch(`${import.meta.env.VITE_API_URL}/agendamentos`);
const { data } = await response.json();
console.log(data);
```

**Criar agendamento**
```js
const response = await fetch(`${import.meta.env.VITE_API_URL}/agendamentos`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        cliente_id: 1,
        servico_id: 1,
        data: '2025-08-10',
        hora: '09:00',
    }),
});

if (!response.ok) {
    const errors = await response.json();
    console.error(errors); // { message: '...', errors: { campo: [...] } }
    return;
}

const { data } = await response.json();
console.log(data);
```

---

### Exemplos com Axios

**Instalação**
```bash
npm install axios
```

**Configuração (ex: `src/api.js`)**
```js
import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

export default api;
```

**Uso nos componentes**
```js
import api from './api';

// Listar agendamentos
const { data } = await api.get('/agendamentos', {
    params: { data: '2025-08-10', status: 'agendado' },
});

// Criar agendamento
const { data } = await api.post('/agendamentos', {
    cliente_id: 1,
    servico_id: 1,
    data: '2025-08-10',
    hora: '09:00',
});

// Atualizar status
await api.patch(`/agendamentos/${id}/status`, { status: 'realizado' });

// Cancelar
await api.delete(`/agendamentos/${id}`);
```

**Tratamento de erros de validação (422)**
```js
try {
    await api.post('/agendamentos', payload);
} catch (error) {
    if (error.response?.status === 422) {
        const { errors } = error.response.data;
        // errors = { campo: ['mensagem de erro'] }
        console.error(errors);
    }
}
```

---

## Estrutura do projeto

```
app/
├── Dev1/                  # Módulo Clientes
│   ├── Controllers/ClienteController.php
│   ├── Models/Cliente.php
│   └── Requests/ClienteRequest.php
├── Dev2/                  # Módulo Serviços
│   ├── Controllers/ServicoController.php
│   ├── Models/Servico.php
│   └── Requests/ServicoRequest.php
└── Dev3/                  # Módulo Agendamentos
    ├── Controllers/AgendamentoController.php
    ├── Models/Agendamento.php
    ├── Requests/AgendamentoRequest.php
    └── Services/AgendamentoService.php

database/
├── migrations/
│   ├── ..._create_clientes_table.php
│   ├── ..._create_servicos_table.php
│   └── ..._create_agendamentos_table.php
└── seeders/
    ├── ClienteSeeder.php
    ├── ServicoSeeder.php
    └── AgendamentoSeeder.php

routes/
└── api.php
```

---

## Comandos úteis

```bash
# Ver todos os containers
docker compose ps

# Parar os containers
docker compose down

# Acessar o terminal do container
docker compose exec app bash

# Rodar migrations do zero (apaga tudo e recria)
docker compose exec app php artisan migrate:fresh --seed

# Ver todas as rotas registradas
docker compose exec app php artisan route:list

# Ver logs da aplicação
docker compose exec app php artisan pail
```
