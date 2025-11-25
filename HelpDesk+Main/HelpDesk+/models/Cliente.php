<?php
require_once __DIR__ . '/../conexao.php';

class Cliente {
    public $codigo;
    public $nome;
    public $cpf;
    public $email;

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
        $result = select('Cliente', ['*']);
        $clientes = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $clientes[] = new self($row);
            }
        }
        return $clientes;
    }

    public static function buscarPorId($id) {
        global $conn;
        $result = selectWhere('Cliente', ['*'], "codigo = " . (int)$id);
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public static function buscarPorCpf($cpf) {
        global $conn;
        $result = selectWhere('Cliente', ['*'], "cpf = '".mysqli_real_escape_string($conn, $cpf)."'");
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
            'email' => $this->email
        ];
        return insert(['nome', 'cpf', 'email'], $dados, 'Cliente');
    }

    public function atualizar() {
        global $conn;
        $alteracoes = [];
        if (isset($this->nome)) $alteracoes['nome'] = $this->nome;
        if (isset($this->cpf)) $alteracoes['cpf'] = $this->cpf;
        if (isset($this->email)) $alteracoes['email'] = $this->email;
        
        if (!empty($alteracoes)) {
            return update('Cliente', $alteracoes, "codigo = " . $this->codigo);
        }
        return false;
    }

    public function excluir() {
        global $conn;
        return delete('Cliente', "codigo = " . $this->codigo);
    }

    public function temChamados() {
        global $conn;
        $result = selectWhere('Chamado', ['codigo'], "Id_cliente = " . $this->codigo);
        return $result && mysqli_num_rows($result) > 0;
    }
}

