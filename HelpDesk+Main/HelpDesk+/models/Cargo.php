<?php
require_once __DIR__ . '/../conexao.php';

class Cargo {
    public $codigo;
    public $nome;

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
        $result = select('Cargo', ['*']);
        $cargos = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cargos[] = new self($row);
            }
        }
        return $cargos;
    }

    public static function buscarPorId($id) {
        global $conn;
        $result = selectWhere('Cargo', ['*'], "codigo = " . (int)$id);
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public static function buscarPorNome($nome) {
        global $conn;
        $result = selectWhere('Cargo', ['*'], "LOWER(nome) = '".mysqli_real_escape_string($conn, strtolower($nome))."'");
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public function salvar() {
        global $conn;
        $dados = ['nome' => $this->nome];
        return insert(['nome'], $dados, 'Cargo');
    }

    public function atualizar() {
        global $conn;
        if (isset($this->nome)) {
            return update('Cargo', ['nome' => $this->nome], "codigo = " . $this->codigo);
        }
        return false;
    }

    public function excluir() {
        global $conn;
        return delete('Cargo', "codigo = " . $this->codigo);
    }

    public function temFuncionarios() {
        global $conn;
        $result = selectWhere('Funcionario', ['codigo'], "id_cargo = " . $this->codigo);
        return $result && mysqli_num_rows($result) > 0;
    }
}

