# Desafio

Este é um projeto desenvolvido em [Laravel], estruturado diretamente para o repositório GitHub.

## Tecnologias Utiliza
* **Framework:** Laravel 13.x (ou a versão instalada)
* **Linguagem:** PHP 8.3+
* **Gestor de Dependências:** Composer
* **Front-end:** Livewire 4 + Bootstrap 5 + Vite
* **Base de Dados:** MySQL 8+ (utf8mb4)
* **Autenticação da API:** Laravel Sanctum (token Bearer)
* **Testes:** Pest + Laravel

## Estrutura Base (DTOs e Enum)

Os dados validados são transportados entre camadas通过 DTOs (`final readonly`), nunca arrays soltos nem o `Request` directamente.

| Ficheiro | Propriedades |
|----------|-------------|
| `app/DTOs/RegisterData.php` | `name` (string), `email` (string), `password` (string) |
| `app/DTOs/LoginData.php` | `email` (string), `password` (string) |
| `app/DTOs/CreateTaskData.php` | `title` (string), `description` (?string, opcional) |
| `app/DTOs/UpdateTaskStatusData.php` | `status` (`App\Enums\TaskStatus`) |
| `app/DTOs/TaskFilterData.php` | `status` (?`TaskStatus`), `title` (?string), `userId` (?int) — todos opcional |

O Enum `app/Enums/TaskStatus.php` define os três valores possíveis:

* `pending` → "Pendente"
* `in_progress` → "Em andamento"
* `completed` → "Concluída"

## Como Executar o Projeto Localmente

Se precisar de clonar este repositório noutra máquina ou se outra pessoa for trabalhar no projeto, siga estes passos para o colocar a funcionar:

### 1. Clonar o repositório
```bash
git clone https://github.com
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

Configurar a base de dados MySQL (duas bases: `todo_list` para desenvolvimento, `todo_list_test` para testes):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_list
DB_USERNAME=<seu-usuario>
DB_PASSWORD=<sua-senha>
```

### 4. Gerar a Chave da Aplicação
Instalações limpas do Laravel exigem uma chave de criptografia única para funcionar:
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
O projeto estará disponível no seu navegador através do endereço: `http://127.0.0.1:8000`

---

## Próximos Passos do Projeto

- [x] Estrutura base de DTOs e Enum `TaskStatus` criada
- [ ] Configurar a ligação à Base de Dados.
- [ ] Criar as Migrations e Models iniciais.
- [ ] Desenvolver as primeiras rotas e controladores. Desenvolver as primeiras rotas e controladores.
