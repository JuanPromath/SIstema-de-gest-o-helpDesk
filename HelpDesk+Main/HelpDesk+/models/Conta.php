<?php
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/Funcionario.php';

class Conta {
    public $codigo;
    public $senha;
    public $Id_funcionario;
    public $funcionario_nome; // Para JOINs

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
        $query = "SELECT cs.codigo, cs.senha, cs.Id_funcionario, f.nome as funcionario_nome 
                  FROM Conta_Sistema cs 
                  INNER JOIN Funcionario f ON cs.Id_funcionario = f.codigo";
        $result = mysqli_query($conn, $query);
        $contas = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $contas[] = new self($row);
            }
        }
        return $contas;
    }

    public static function buscarPorId($id) {
        global $conn;
        $result = selectWhere('Conta_Sistema', ['*'], "codigo = " . (int)$id);
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public static function buscarPorFuncionarioId($funcionarioId) {
        global $conn;
        $result = selectWhere('Conta_Sistema', ['*'], "Id_funcionario = " . (int)$funcionarioId);
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public function verificarSenha($senha) {
        return password_verify($senha, $this->senha);
    }

    public function salvar() {
        global $conn;
        $dados = [
            'senha' => $this->senha,
            'Id_funcionario' => $this->Id_funcionario
        ];
        return insert(['senha', 'Id_funcionario'], $dados, 'Conta_Sistema');
    }

    public function atualizar() {
        global $conn;
        $alteracoes = [];
        if (isset($this->senha)) $alteracoes['senha'] = $this->senha;
        if (isset($this->Id_funcionario)) $alteracoes['Id_funcionario'] = $this->Id_funcionario;
        
        if (!empty($alteracoes)) {
            return update('Conta_Sistema', $alteracoes, "codigo = " . $this->codigo);
        }
        return false;
    }

    public function excluir() {
        global $conn;
        return delete('Conta_Sistema', "codigo = " . $this->codigo);
    }
}

