<?php
require_once ("include/clsBase.inc.php");
require_once ("include/clsCadastro.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmieducar/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "Prefeitura de Itaja&iacute; - Cadastro Categoria N&iacute;vel" );
		$this->processoAp = "829";
	}
}

class indice extends clsCadastro
{
	/**
	 * Referencia pega da session para o idpes do usuario atual
	 *
	 * @var int
	 */
	var $pessoa_logada;

	var $cod_categoria_nivel;
	var $ref_usuario_exc;
	var $ref_usuario_cad;
	var $nm_categoria_nivel;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;

	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$this->cod_categoria_nivel=$_GET["cod_categoria_nivel"];

		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_cadastra( 829, $this->pessoa_logada, 3,  "educar_categoria_nivel_lst.php", true );

		if( is_numeric( $this->cod_categoria_nivel ) )
		{

			$obj = new clsPmieducarCategoriaNivel( $this->cod_categoria_nivel );
			$registro  = $obj->detalhe();
			if( $registro )
			{
				foreach( $registro AS $campo => $val )	// passa todos os valores obtidos no registro para atributos do objeto
					$this->$campo = $val;


			$obj_permissoes = new clsPermissoes();
			if( $obj_permissoes->permissao_excluir( 829, $this->pessoa_logada, 3, null, true ) )
			{
				$this->fexcluir = true;
			}

				$retorno = "Editar";
			}
		}
		$this->url_cancelar = ($retorno == "Editar") ? "educar_categoria_nivel_det.php?cod_categoria_nivel={$registro["cod_categoria_nivel"]}" : "educar_categoria_nivel_lst.php";
		$this->nome_url_cancelar = "Cancelar";
		return $retorno;
	}

	function Gerar()
	{
		// primary keys
		$this->campoOculto( "cod_categoria_nivel", $this->cod_categoria_nivel );

		// foreign keys

		// text
		$this->campoTexto( "nm_categoria_nivel", "Nome Categoria", $this->nm_categoria_nivel, 30, 255, true );


	}

	function Novo()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_cadastra( 829, $this->pessoa_logada, 3,  "educar_categoria_nivel_lst.php", true );


		$obj = new clsPmieducarCategoriaNivel( $this->cod_categoria_nivel, $this->pessoa_logada, $this->pessoa_logada, $this->nm_categoria_nivel, $this->data_cadastro, $this->data_exclusao, $this->ativo );
		$cadastrou = $obj->cadastra();
		if( $cadastrou )
		{
			$this->mensagem .= "Cadastro efetuado com sucesso.<br>";
			header( "Location: educar_categoria_nivel_lst.php" );
			die();
			return true;
		}

		$this->mensagem = "Cadastro n&atilde;o realizado.<br>";
		echo "<!--\nErro ao cadastrar clsPmieducarCategoriaNivel\nvalores obrigatorios\nis_numeric( $this->ref_usuario_cad ) && is_string( $this->nm_categoria_nivel )\n-->";
		return false;
	}

	function Editar()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_cadastra( 829, $this->pessoa_logada, 3,  "educar_categoria_nivel_lst.php", true );


		$obj = new clsPmieducarCategoriaNivel($this->cod_categoria_nivel, $this->pessoa_logada, $this->pessoa_logada, $this->nm_categoria_nivel, $this->data_cadastro, $this->data_exclusao, $this->ativo);
		$editou = $obj->edita();
		if( $editou )
		{
			$this->mensagem .= "Edi&ccedil;&atilde;o efetuada com sucesso.<br>";
			header( "Location: educar_categoria_nivel_lst.php" );
			die();
			return true;
		}

		$this->mensagem = "Edi&ccedil;&atilde;o n&atilde;o realizada.<br>";
		echo "<!--\nErro ao editar clsPmieducarCategoriaNivel\nvalores obrigatorios\nif( is_numeric( $this->cod_categoria_nivel ) && is_numeric( $this->ref_usuario_exc ) )\n-->";
		return false;
	}

	function Excluir()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_excluir( 829, $this->pessoa_logada, 3,  "educar_categoria_nivel_lst.php", true );


		$obj = new clsPmieducarCategoriaNivel($this->cod_categoria_nivel, $this->pessoa_logada, $this->pessoa_logada, $this->nm_categoria_nivel, $this->data_cadastro, $this->data_exclusao, 0);
		$excluiu = $obj->excluir();
		if( $excluiu )
		{
			$this->mensagem .= "Exclus&atilde;o efetuada com sucesso.<br>";
			header( "Location: educar_categoria_nivel_lst.php" );
			die();
			return true;
		}

		$this->mensagem = "Exclus&atilde;o n&atilde;o realizada.<br>";
		echo "<!--\nErro ao excluir clsPmieducarCategoriaNivel\nvalores obrigatorios\nif( is_numeric( $this->cod_categoria_nivel ) && is_numeric( $this->ref_usuario_exc ) )\n-->";
		return false;
	}
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na clsBase
$pagina->addForm( $miolo );
// gera o html
$pagina->MakeAll();
?>