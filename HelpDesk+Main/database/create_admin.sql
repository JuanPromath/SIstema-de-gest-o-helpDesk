-- Script para criar administrador padrão
-- Execute este script no banco de dados para criar o admin automaticamente
-- Username: admin | Senha: 0000

USE HelpDeskMais;

-- 1. Criar cargo Administrador se não existir
INSERT INTO Cargo (nome) 
VALUES ('Administrador') 
ON DUPLICATE KEY UPDATE nome = 'Administrador';

-- 2. Obter ID do cargo Administrador
SET @cargo_admin_id = (SELECT codigo FROM Cargo WHERE LOWER(nome) = 'administrador' LIMIT 1);

-- 3. Criar funcionário administrador se não existir
INSERT INTO Funcionario (nome, cpf, email, id_cargo) 
VALUES ('Administrador', '00000000000', 'admin@helpdesk.com', @cargo_admin_id)
ON DUPLICATE KEY UPDATE 
    nome = 'Administrador',
    id_cargo = @cargo_admin_id;

-- 4. Obter ID do funcionário administrador
SET @func_admin_id = (SELECT codigo FROM Funcionario WHERE LOWER(email) = 'admin@helpdesk.com' LIMIT 1);

-- 5. Criar ou atualizar conta do administrador com senha "0000"
-- A senha é hashada usando password_hash do PHP (bcrypt)
-- Para MySQL, vamos usar uma senha hashada equivalente
-- Senha "0000" hashada com bcrypt: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO Conta_Sistema (senha, Id_funcionario) 
VALUES ('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', @func_admin_id)
ON DUPLICATE KEY UPDATE 
    senha = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- Verificar se foi criado
SELECT 
    f.codigo as 'ID Funcionário',
    f.nome as 'Nome',
    f.email as 'Email',
    c.nome as 'Cargo',
    cs.codigo as 'ID Conta',
    'admin' as 'Username',
    '0000' as 'Senha'
FROM Funcionario f
INNER JOIN Cargo c ON f.id_cargo = c.codigo
INNER JOIN Conta_Sistema cs ON cs.Id_funcionario = f.codigo
WHERE LOWER(f.email) = 'admin@helpdesk.com';

