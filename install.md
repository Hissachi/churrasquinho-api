# Setup do Backend - Projeto Churrasquinho

Este guia explica como configurar o ambiente do backend após clonar o repositório. Siga os passos na ordem para evitar erros.

---

# 1. Instalar o Laragon

1. Baixe o Laragon:

[https://laragon.org/download/](https://laragon.org/download/)

2. Instale normalmente.

3. Após instalar, abra o Laragon.

4. Clique em:

Start All

Você deve ver:

* Apache: Running
* MySQL: Running

---

# 2. Clonar o projeto

Abra o terminal e execute:

```bash
git clone <URL_DO_REPOSITORIO>
cd churrasquinho-api
```

---

# 3. Instalar dependências do Laravel

Dentro da pasta do projeto execute:

```bash
composer install
```

Isso irá instalar todas as dependências do projeto.

---

# 4. Criar o arquivo .env

Copie o arquivo de exemplo:

```bash
cp .env.example .env
```

Se estiver no Windows e o comando não funcionar, copie manualmente o arquivo.

---

# 5. Gerar a chave da aplicação

Execute:

```bash
php artisan key:generate
```

Isso irá preencher automaticamente a variável APP_KEY no arquivo `.env`.

---

# 6. Criar o banco de dados

1. Abra o Laragon
2. Menu → MySQL → HeidiSQL

Crie um banco chamado:

```
churrasquinho
```

Charset recomendado:

```
utf8mb4_general_ci
```

---

# 7. Configurar conexão com o banco

Abra o arquivo `.env` e configure:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=churrasquinho
DB_USERNAME=root
DB_PASSWORD=
```

Normalmente no Laragon o usuário é `root` e a senha é vazia.

---

# 8. Limpar cache de configuração

Execute:

```bash
php artisan config:clear
php artisan cache:clear
```

---

# 9. Rodar as migrations

Execute:

```bash
php artisan migrate
```

Isso criará todas as tabelas necessárias no banco.

---

# 10. Rodar o servidor

Execute:

```bash
php artisan serve
```

A aplicação ficará disponível em:

```
http://127.0.0.1:8000
```

Se a página do Laravel aparecer, o backend está funcionando corretamente.

---

# Estrutura esperada após setup

Você deve ter:

* Laragon rodando
* MySQL ativo
* Banco `churrasquinho` criado
* Dependências instaladas
* Migrations executadas

---

# Problemas comuns

## Erro: MissingAppKeyException

Execute:

```bash
php artisan key:generate
```

## Erro: Database does not exist

Crie o banco `churrasquinho` no HeidiSQL.

## Erro ao conectar no banco

Verifique as variáveis no `.env`.

---

# Pronto

Se tudo foi executado corretamente, o backend estará pronto para desenvolvimento.
