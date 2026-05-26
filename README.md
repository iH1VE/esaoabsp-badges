# ESA OABSP Badges

Sistema de emissão e gerenciamento de badges digitais desenvolvido para a ESA OABSP (Escola Superior de Advocacia da OAB São Paulo).

A plataforma foi criada para automatizar processos de certificação digital, reconhecimento de conquistas acadêmicas e distribuição de badges em trilhas de aprendizagem.

---

## Sobre o projeto

O sistema permite a emissão automatizada de badges digitais para alunos, participantes e usuários que completam cursos, eventos ou trilhas educacionais dentro da plataforma da ESA OABSP.

O projeto foi desenvolvido com foco em:

- Escalabilidade
- Automação de emissões
- Organização acadêmica
- Gestão de trilhas
- Controle administrativo
- Integração com banco de dados
- Facilidade operacional

---

## Tecnologias utilizadas

- PHP 8+
- Laravel
- MySQL
- Blade Templates
- JavaScript
- Bootstrap / CSS
- Linux Server
- Git

---

## Funcionalidades

- Emissão automatizada de badges
- Gerenciamento de trilhas de aprendizagem
- Painel administrativo
- Controle de usuários
- Associação de badges por regras
- Emissão individual e em massa
- Sistema de conquistas acadêmicas
- Estrutura modular para expansão
- Layout responsivo
- Integração com banco de dados MySQL
- Controle de status e progressão
- Sistema preparado para deploy em VPS/Linux

---

## Estrutura do projeto

```bash
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
```

---

## Instalação

Clone o repositório:

```bash
git clone https://github.com/iH1VE/esaoabsp-badges.git
```

Acesse a pasta:

```bash
cd esaoabsp-badges
```

Instale as dependências:

```bash
composer install
```

Configure o ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Configure o banco de dados no arquivo `.env`

Execute as migrations:

```bash
php artisan migrate
```

Inicie o servidor local:

```bash
php artisan serve
```

---

## Fluxo do sistema

1. Cadastro de usuários/alunos
2. Associação de cursos e trilhas
3. Configuração das badges
4. Definição de regras de emissão
5. Processamento automático de conquistas
6. Emissão digital das badges
7. Controle administrativo e auditoria

---

## Ambiente de produção

Recomendado:

- Ubuntu/Debian
- Nginx ou Apache
- PHP-FPM
- MySQL/MariaDB
- SSL via Let's Encrypt
- Supervisor (opcional)

---

## Segurança

O projeto utiliza:

- Variáveis de ambiente via `.env`
- Proteções nativas do Laravel
- Controle de acesso administrativo
- Gerenciamento seguro de credenciais
- Estrutura desacoplada entre frontend e backend

---

## Diferenciais do sistema

- Estrutura preparada para grandes volumes de emissão
- Automatização de processos acadêmicos
- Facilidade de manutenção
- Escalabilidade para novas trilhas e badges
- Redução de trabalho operacional manual
- Organização institucional moderna

---

## Versionamento

Projeto versionado utilizando Git e hospedado no GitHub.

---

## Autor

Desenvolvido por William Merces

GitHub:
https://github.com/iH1VE

---

## Licença

Este projeto possui uso institucional e privado.

Todos os direitos reservados.
