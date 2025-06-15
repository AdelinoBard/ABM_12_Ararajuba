# Ararajuba

## Visão Geral

O Ararajuba é uma rede social simples com recursos essenciais para interação entre usuários. O projeto foi desenvolvido em PHP, MySQL, JavaScript e CSS, seguindo boas práticas de segurança e usabilidade.

## Características Principais

- Cadastro e autenticação de usuários
- Perfis personalizáveis com imagens e texto
- Sistema de amizades entre membros
- Mensagens públicas e privadas
- Interface responsiva para dispositivos móveis e desktop

## Estrutura do Projeto

### Arquivos Principais

#### `functions.php` [Detalhes](functions.md)

- **Funções**:
  - `destroySession`: Encerra sessões de usuários.
  - `mostrarPerfil`: Exibe informações e imagem do perfil.
- **Segurança**:
  - Uso de PDO para conexão com banco de dados
  - Instruções preparadas para prevenir SQL injection

#### `header.php` [Detalhes](header.md)

- Inicializa sessões
- Define estrutura HTML comum a todas as páginas
- Controle de menus baseado no estado de login

#### `setup.php` [Detalhes](setup.md)

- Configuração inicial do banco de dados
- Cria tabelas:
  - `members` (usuários e senhas)
  - `messages` (mensagens entre usuários)
  - `friends` (relacionamentos de amizade)
  - `profiles` (informações de perfil)

### Fluxo do Usuário

1. **Cadastro** (`signup.php`) [Detalhes](signup.md.md)

   - Validação assíncrona de nomes de usuário disponíveis
   - Armazenamento seguro de senhas usando hash

2. **Login** (`login.php`) [Detalhes](login.md)

   - Autenticação segura com `password_verify()`
   - Gerenciamento de sessões

3. **Perfil** (`profile.php`) [Detalhes](profile.md)

   - Upload e processamento de imagens:
     - Redimensionamento
     - Ajuste de nitidez
     - Validação de tipo
   - Edição de texto "Sobre mim"

4. **Interação Social**

   - `members.php` [Detalhes](members.md): Lista de membros e gestão de amizades
   - `friends.php`[Detalhes](friends.md): Visualização de amigos e seguidores
   - `messages.php`[Detalhes](messages.md): Sistema de mensagens públicas/privadas

5. **Logout** (`logout.php`) [Detalhes](logout.md)
   - Encerramento seguro de sessões

## Configuração do Banco de Dados

```sql
CREATE DATABASE ararajuba;
CREATE USER 'abm'@'localhost' IDENTIFIED BY 'B@rd';
GRANT ALL PRIVILEGES ON ararajuba.* TO 'abm'@'localhost';
```

**Aviso**: A senha padrão deve ser alterada em ambientes de produção.

## Boas Práticas Implementadas

1. **Segurança**:

   - Hash de senhas
   - Proteção contra XSS (com `htmlentities`)
   - Validação de tipos de arquivo no upload
   - Uso de PDO com prepared statements

2. **Usabilidade**:

   - Interface responsiva
   - Feedback visual claro (ícones de status)
   - Formulários intuitivos

3. **Performance**:
   - Redimensionamento de imagens no servidor
   - Consultas SQL otimizadas

## Estilos e Scripts

- `styles.css` [Detalhes](styles.md): Define a aparência visual
- `javascript.js`[Detalhes](javascript.md): Contém funções auxiliares para manipulação DOM

## Requisitos

- PHP com suporte a PDO
- MySQL
- Biblioteca GD para processamento de imagens

## Notas Adicionais

O projeto foi nomeado em homenagem à ararajuba, ave brasileira em extinção, simbolizando a importância da conservação e conexão entre indivíduos.

Este repositório foi criado para acompanhar o aprendizado do livro Learning PHP, MySQL & JavaScript: A Step-by-Step Guide to Creating Dynamic Websites (Seventh Edition), escrito pelo professor e autor Robin Nixon e publicado pela O'Reilly Media.

Para implementação em produção, recomenda-se:

1. Substituir as credenciais padrão
2. Implementar tratamento de erros mais robusto
3. Adicionar medidas adicionais de segurança como CSRF protection
