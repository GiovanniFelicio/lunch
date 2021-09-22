<?php


namespace App;


class Valores
{
    /**Tipo de relatorio**/
    public const VENDAS = 1;
    public const DADOS = 2;

    /*Período Data*/
    
    public const DIA = 1;
    public const MES = 2;
    public const ANO = 3;
    /*Forma venda*/
    
    public const QUANT = 1;
    public const APAGAR = 2;
    
    public const CADASTRADO = 1;
    public const NAOCADASTRADO = 2;

    public const MODERADOR = 1;
    public const ADMINISTRADOR = 2;
    public const MASTER = 3;

    /** Errors */

    public const ERROVALIDACAO = 100;
    public const CONSUNOTFOUND = 101;
    public const CATEGNOTFOUND = 102;
    public const INSUFICIENTE = 103;
    public const AUTORIZADO = 104;
    public const ERRORINTERNAL = 500;
    public const ERRRORPERMISSAO = 403;
    public const MAXLIMIT = 501;

}