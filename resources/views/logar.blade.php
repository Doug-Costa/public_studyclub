<?php
$tipoTopo = 'topoPreto';
?>

@extends('layouts.master')

@section('content')

@if($errors->has('logado') || $errors->has('logadoVencido') || $errors->has('logadoSem'))
    <script>window.location.href = "{{ route('faceindex') }}";</script>
@endif

<div class="container" style="padding-top: 30px;">
	<div class="row">
		<div class="col-sm-4"></div>
		<div class="col-sm-4">

			@if($errors->has('errousuario'))
				<div class="alert alert-danger">Usuário não encontrado.</div>
			@elseif($errors->has('errosenha'))
				<div class="alert alert-danger">Senha incorreta.</div>
			@elseif($errors->has('errosenhaNova'))
				<div class="alert alert-warning">Por favor, solicite uma nova senha.</div>
			@endif

			<form method="POST" action="{{ route('login') }}" enctype="multipart/form-data">
	            @csrf

	            <div class="row">
	              <div class="mb-3">
	                <label for="E-mail" class="form-label">{{__("messages.LogarBladeEmail")}}</label>
	                <input type="email" name="email" class="form-control" id="emailLoginLabel" aria-describedby="emailLogin" value="{{ old('email') }}">
	                <div id="emailLogin" class="form-text">{{__("messages.LogarBladeCadastrado")}}</div>
	              </div>
	            </div>

	            <div class="row">
	              <div class="mb-3">
	                <label for="password" class="form-label">{{__("messages.LogarBladeCadastrado")}}</label>
	                <input type="password" name="password" class="form-control" id="senhaLogin" aria-describedby="passoword">
	              </div>
	            </div>

	            <div class="row">
	              <div class="mb-3">
	                <input type="submit" value="Logar" class="btn btn-danger dropdown-toggle botaoLogar">
	              </div>
	            </div>
	                
	         </form>
		</div>
		<div class="col-sm-4"></div>
	</div>
</div>

@endsection