# 📋 To-Do List API & Web App

## 🏢 Contexto

| Detalhe      | Informação                                        |
|--------------|---------------------------------------------------|
| **Empresa**  | Pacheco Barroso                                   |
| **Vaga**     | Programador                                       |
| **Entrevista** | 11/06/2025 — Condomínio TSE, villa 17 (frente à academia Bai) |

---

## 🎯 Objectivo

Desenvolver uma **API RESTful** em **Laravel** com funcionalidades completas de gestão de tarefas (To-Do List), incluindo uma **interface web** utilizando **Bootstrap 5** e **Livewire 3**.

---

## 🔧 Funcionalidades da API

| Ação               | Descrição                                                        |
|--------------------|------------------------------------------------------------------|
| Criar tarefa       | Criar tarefa com `título (obrigatório)` e `descrição`.           |
| Listar tarefas     | Listar **todas as tarefas** do utilizador autenticado.           |
| Atualizar status   | Atualizar status da tarefa: `pendente`, `em andamento`, `concluída`. |
| Deletar tarefa     | Excluir tarefa existente.                                        |
| Filtrar por status | Permitir filtro por `status`.                                    |

## 🖥️ Funcionalidades da App Web (Livewire 3 + Bootstrap 5)

| Ação     | Descrição                                                        |
|----------|------------------------------------------------------------------|
| Criar tarefa | Criar tarefa com `título (obrigatório)` e `descrição`.       |
| Listar tarefas | Listar todas as tarefas.                                   |
| Atualizar status | Alterar status: `pendente`, `em andamento`, `concluída`. |
| Deletar tarefa | Excluir tarefas.                                           |
| Filtros  | Filtrar por `status`, `título` e `utilizador`.                   |

---

## ✅ Validações da API

| Campo    | Regras de Validação                                            |
|----------|----------------------------------------------------------------|
| `title`  | Obrigatório (`required`)                                       |
| `status` | Deve ser um dos: `pending`, `in_progress`, `completed`         |

## 🔁 Respostas da API

| Código HTTP | Situação                              |
|-------------|---------------------------------------|
| 200         | OK (requisição bem-sucedida)          |
| 201         | Created (recurso criado)              |
| 404         | Not Found (recurso não encontrado)    |
| 422         | Validation Error (erro de validação)  |

---

## 🗄️ Base de Dados

- **MySQL** (`utf8mb4`), duas bases: `todo_list` (desenvolvimento) e `todo_list_test` (testes).
- **Eloquent ORM** para todas as interações com a base de dados.
- **Migrations** para a estrutura das tabelas.

## 🧪 Testes

| Teste                | Descrição                              |
|----------------------|----------------------------------------|
| Criar tarefa         | Testar criação com dados válidos.      |
| Listar tarefas       | Testar listagem de tarefas.            |
| Atualizar status     | Testar atualização de status.          |
| Deletar tarefa       | Testar exclusão de tarefa.             |
| Filtrar por status   | Testar filtro de tarefas por status.   |

---

## 🛠️ Tecnologias Utilizadas

* **Framework:** Laravel 13.x
* **Linguagem:** PHP 8.3+
* **Gestor de Dependências:** Composer
* **Front-end:** Livewire 3 + Bootstrap 5 + Vite
* **Base de Dados:** MySQL 8+ (utf8mb4)
* **Autenticação da API:** Laravel Sanctum (token Bearer)
* **Testes:** Pest + Laravel

## 🏗️ Arquitectura — DTOs e Enum

Os dados validados são transportados entre camadas através de DTOs (`final readonly`), nunca arrays soltos nem o `Request` directamente.

| Ficheiro | Propriedades |
|----------|--------------|
| `app/DTOs/RegisterData.php` | `name` (string), `email` (string), `password` (string) |
| `app/DTOs/LoginData.php` | `email` (string), `password` (string) |
| `app/DTOs/CreateTaskData.php` | `title` (string), `description` (?string, opcional) |
| `app/DTOs/UpdateTaskStatusData.php` | `status` (`App\Enums\TaskStatus`) |
| `app/DTOs/TaskFilterData.php` | `status` (?`TaskStatus`), `title` (?string), `userId` (?int) — todos opcionais |

O Enum `app/Enums/TaskStatus.php` define os três valores possíveis:

* `pending` → "Pendente"
* `in_progress` → "Em andamento"
* `completed` → "Concluída"

---

## 📦 Como Executar o Projeto Localmente

### 1. Clonar o repositório
```bash
git clone https://github.com/<usuario>/Desafio_pb-main.git
cd Desafio_pb-main
```

### 2. Instalar as dependências do Composer
```bash
composer install
```

### 3. Configurar o Ambiente (`.env`)
O ficheiro `.env` contém configurações confidenciais e não é enviado para o GitHub. Copie o modelo padrão:
```bash
cp .env.example .env
```

Configure a base de dados MySQL:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_list
DB_USERNAME=<seu-usuario>
DB_PASSWORD=<sua-senha>
```

### 4. Gerar a Chave da Aplicação
```bash
php artisan key:generate
```

### 5. Instalar e construir o front-end
```bash
npm install
npm run build
```

### 6. Executar as migrations (e seed)
```bash
php artisan migrate --seed
```

### 7. Iniciar o Servidor Local
```bash
php artisan serve
```
O projeto estará disponível no navegador através do endereço: `http://127.0.0.1:8000`

---

## 📝 Estado do Projecto

- [x] Etapa 0 — ambiente local, DTOs, Git (Gitflow) e agente configurados
- [x] Etapa 1 — base de dados (`tasks`), Model `Task`, `TaskFactory` e `DatabaseSeeder`
- [x] Etapa 2 — autenticação da API (Sanctum)
- [x] Etapa 3 — API de tarefas (`/api/v1`)
- [x] Etapa 4 — testes automatizados da API
- [x] Etapa 5 — web app (Livewire 3 + Bootstrap 5)
- [x] Etapa 6 — documentação Swagger (OpenAPI)
- [x] Etapa 7 — README final e finalização

## 🧑‍💻 Boas Práticas

- Arquitectura MVC + Design Patterns; nomes claros em inglês.
- Gitflow (`main`, `develop`, `feature/<nome>`) com commits claros e frequentes (Conventional Commits).
- Tratamento de erros com respostas e mensagens claras.
- Documentação Swagger/OpenAPI com todos os endpoints, métodos, parâmetros, exemplos e códigos HTTP. e pode ser visualizado http://your-app.test/api/documentation

---