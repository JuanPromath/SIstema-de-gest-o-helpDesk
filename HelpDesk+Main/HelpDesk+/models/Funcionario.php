<?php
require_once __DIR__ . '/../conexao.php';

class Funcionario {
    public $codigo;
    public $nome;
    public $cpf;
    public $email;
    public $id_cargo;
    public $cargo_nome; // Para JOINs

    public function __construct($dados = []) {
        if (!empty($dados)) {
            foreach ($dados as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public static function buscarTodos() {
        global $conn;
        $query = "SELECT f.codigo, f.nome, f.cpf, f.email, f.id_cargo, c.nome as cargo_nome 
                  FROM Funcionario f 
                  INNER JOIN Cargo c ON f.id_cargo = c.codigo";
        $result = mysqli_query($conn, $query);
        $funcionarios = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $funcionarios[] = new self($row);
            }
        }
        return $funcionarios;
    }

    public static function buscarPorId($id) {
        global $conn;
        $query = "SELECT f.codigo, f.nome, f.cpf, f.email, f.id_cargo, c.nome as cargo_nome 
                  FROM Funcionario f 
                  INNER JOIN Cargo c ON f.id_cargo = c.codigo 
                  WHERE f.codigo = " . (int)$id;
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public static function buscarPorCpf($cpf) {
        global $conn;
        $result = selectWhere('Funcionario', ['*'], "cpf = '".mysqli_real_escape_string($conn, $cpf)."'");
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public static function buscarPorEmail($email) {
        global $conn;
        $result = selectWhere('Funcionario', ['*'], "email = '".mysqli_real_escape_string($conn, $email)."'");
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public function salvar() {
        global $conn;
        $dados = [
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'id_cargo' => $this->id_cargo
        ];
        return insert(['nome', 'cpf', 'email', 'id_cargo'], $dados, 'Funcionario');
    }

    public function atualizar() {
        global $conn;
        $alteracoes = [];
        if (isset($this->nome)) $alteracoes['nome'] = $this->nome;
        if (isset($this->cpf)) $alteracoes['cpf'] = $this->cpf;
        if (isset($this->email)) $alteracoes['email'] = $this->email;
        if (isset($this->id_cargo)) $alteracoes['id_cargo'] = $this->id_cargo;
        
        if (!empty($alteracoes)) {
            return update('Funcionario', $alteracoes, "codigo = " . $this->codigo);
        }
        return false;
    }

    public function excluir() {
        global $conn;
        return delete('Funcionario', "codigo = " . $this->codigo);
    }

    public function temChamados() {
        global $conn;
        $result = selectWhere('Chamado', ['codigo'], "Id_funcionario = " . $this->codigo);
        return $result && mysqli_num_rows($result) > 0;
    }

    public function isAdmin() {
        return strtolower(trim($this->cargo_nome ?? '')) === 'administrador';
    }
}

