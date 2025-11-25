<?php
require_once __DIR__ . '/../conexao.php';

class Chamado {
    public $codigo;
    public $bo;
    public $status;
    public $Id_cliente;
    public $Id_funcionario;
    public $Id_conta;
    public $id_cargo;
    public $data_abertura;
    public $data_fechamento;
    // Campos de JOIN
    public $nome_cliente;
    public $cpf_cliente;
    public $nome_funcionario;
    public $cargo;

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
        $query = "SELECT c.codigo, c.bo, c.status, c.data_abertura, c.data_fechamento,
                         cl.nome as nome_cliente, cl.cpf as cpf_cliente,
                         f.nome as nome_funcionario, ca.nome as cargo
                  FROM Chamado c
                  INNER JOIN Cliente cl ON c.Id_cliente = cl.codigo
                  INNER JOIN Funcionario f ON c.Id_funcionario = f.codigo
                  INNER JOIN Cargo ca ON c.id_cargo = ca.codigo";
        $result = mysqli_query($conn, $query);
        $chamados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $chamados[] = new self($row);
            }
        }
        return $chamados;
    }

    public static function buscarPorId($id) {
        global $conn;
        $query = "SELECT c.codigo, c.bo, c.status, c.Id_cliente, c.id_cargo, c.Id_conta, c.Id_funcionario,
                         c.data_abertura, c.data_fechamento,
                         cl.nome as nome_cliente, f.nome as nome_funcionario
                  FROM Chamado c
                  INNER JOIN Cliente cl ON c.Id_cliente = cl.codigo
                  INNER JOIN Funcionario f ON c.Id_funcionario = f.codigo
                  WHERE c.codigo = " . (int)$id;
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return new self(mysqli_fetch_assoc($result));
        }
        return null;
    }

    public static function buscarPorStatus($status) {
        global $conn;
        $query = "SELECT c.codigo, c.bo, c.status, c.data_abertura, c.data_fechamento,
                         cl.nome as nome_cliente, cl.cpf as cpf_cliente,
                         f.nome as nome_funcionario, ca.nome as cargo
                  FROM Chamado c
                  INNER JOIN Cliente cl ON c.Id_cliente = cl.codigo
                  INNER JOIN Funcionario f ON c.Id_funcionario = f.codigo
                  INNER JOIN Cargo ca ON c.id_cargo = ca.codigo
                  WHERE c.status = '".mysqli_real_escape_string($conn, $status)."'";
        $result = mysqli_query($conn, $query);
        $chamados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $chamados[] = new self($row);
            }
        }
        return $chamados;
    }

    public function salvar() {
        global $conn;
        $dados = [
            'bo' => $this->bo,
            'Id_cliente' => $this->Id_cliente,
            'id_cargo' => $this->id_cargo,
            'Id_funcionario' => $this->Id_funcionario,
            'Id_conta' => $this->Id_conta,
            'status' => $this->status ?? 'aberto'
        ];
        return insert(['bo', 'Id_cliente', 'id_cargo', 'Id_funcionario', 'Id_conta', 'status'], $dados, 'Chamado');
    }

    public function atualizar() {
        global $conn;
        $alteracoes = [];
        if (isset($this->bo)) $alteracoes['bo'] = $this->bo;
        if (isset($this->status)) {
            $alteracoes['status'] = $this->status;
            // Se fechando, adicionar data de fechamento
            if ($this->status === 'fechado' && !$this->data_fechamento) {
                $alteracoes['data_fechamento'] = date('Y-m-d H:i:s');
            } elseif ($this->status !== 'fechado') {
                $alteracoes['data_fechamento'] = null;
            }
        }
        if (isset($this->Id_cliente)) $alteracoes['Id_cliente'] = $this->Id_cliente;
        if (isset($this->id_cargo)) $alteracoes['id_cargo'] = $this->id_cargo;
        if (isset($this->Id_conta)) {
            $alteracoes['Id_conta'] = $this->Id_conta;
            // Atualizar funcionário baseado na conta
            $conta = selectWhere('Conta_Sistema', ['Id_funcionario'], "codigo = " . $this->Id_conta);
            if ($conta && mysqli_num_rows($conta) > 0) {
                $contaData = mysqli_fetch_assoc($conta);
                $alteracoes['Id_funcionario'] = $contaData['Id_funcionario'];
            }
        }
        
        if (!empty($alteracoes)) {
            return update('Chamado', $alteracoes, "codigo = " . $this->codigo);
        }
        return false;
    }

    public function excluir() {
        global $conn;
        return delete('Chamado', "codigo = " . $this->codigo);
    }
}
