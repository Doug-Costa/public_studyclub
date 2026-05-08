<?php
$paginaTitulo = 'Canal SHINING 3D - DentalGo';
$tipoTopo = 'topoPreto';
$assinar = 0;
function limita_caracteres($texto, $limite, $quebra = true){
   $tamanho = strlen($texto);
   if($tamanho <= $limite){ //Verifica se o tamanho do texto é menor ou igual ao limite
      $novo_texto = $texto;
   }else{ // Se o tamanho do texto for maior que o limite
      if($quebra == true){ // Verifica a opção de quebrar o texto
         $novo_texto = trim(substr($texto, 0, $limite))."...";
      }else{ // Se não, corta $texto na última palavra antes do limite
         $ultimo_espaco = strrpos(substr($texto, 0, $limite), " "); // Localiza o útlimo espaço antes de $limite
         $novo_texto = trim(substr($texto, 0, $ultimo_espaco))."..."; // Corta o $texto até a posição localizada
      }
   }
   return $novo_texto; // Retorna o valor formatado
}

$materias = $canal[4];
$videos = $canal[5];
?>
@extends('facelift2.master')

@section('content')
   <style>
      body{
         background-image: linear-gradient(to right,#e4f0fe,white)
      }

      .page {
         background-color: #e9ecee;
      }

      #videoPlayer {
         width: 100%;
         filter:brightness(60%)
      }

      .conteudoCentro {
   
      }

      .banner-desktop {
         position: absolute;
         top: 25%; 
         left: 10%;
      }

      .dscore-subtitulo {
         margin-top: 60px; 
         max-width: 85%;
      }

      @media (min-width:769px) and (max-width: 992px) {
         .banner-desktop{
            top: 15%;
         }
      }

      @media (min-width:993px) and (max-width: 1186px) {
         .banner-desktop {
            top: 20%;             
         }

         .dscore-subtitulo {
            line-height: 130%;
         }

         .shining-button {
            display: none;
         }
      }

      @media (min-width:993px) and (max-width: 1440px) {
         .dscore-subtitulo {
            margin-top: 20px; 
            padding-bottom: unset;
         }
      }

      .shining-button {
         max-width: 280px;
         border: unset;
         box-shadow: 0 0 4px 0px #FFFFFF;
         border-radius: 8px;
         background: linear-gradient(0.25turn, #c2aa8e, #ffffff, #c2aa8e);
      }

      .shining-button:hover {
         a {
            color: #fff;
            text-shadow: 0 0 10px #000;
         }
      }

      .shining-button a {
         display: block;
         color: #000000;
         font-weight: 800;
         text-decoration: none;
         margin: 12px;
         transition: 0.10s;
      }

      .imgDvi{
         width: 200px;
      }

      .textoDvi{
         color:white;
         padding-bottom: 20px;
         text-align: center !important;
      }

      .slide {
         text-align: center;
         border-radius: 5px;
         margin-bottom: 20px;
         margin: 20px;
      
      }
      .caixaArtigo {
         cursor: pointer;
         font-family: sans-serif;
         font-size: 30px;
         color: gray;
         font-weight: bold;
         text-align: left;
         word-spacing: 5px;
         margin-right: 50px;
      }
      .caixaSubArtigo {
         font-family: sans-serif;
         font-weight: lighter;
         color: #959595;
         text-align: justify;
         font-size: 25px;
         word-spacing: 0px;
         margin-right: 50px;
      }
      .leia-mais {
         display: block;
         margin-top: 10px;
      }
      
      .boxbordas {
         border-top: 3px solid #ccc;
         margin-block: 1rem 3.5rem;
      }

      .leia-mais-btn {
         display: inline-block;
         padding: 10px 20px;
         background-color: #52918d;
         color: #fff;
         font-family: sans-serif;
         border-radius: 10px;
         text-decoration: none !important;
         transition: background-color 0.3s;
         transition: ease 0.5s;
         float: left;
         margin-bottom:50px !important;
         border: 2px solid #52918d !important; 
      }
   
      .leia-mais-btn:hover {
         background-color: #fff;
         color: #52918d;
      }

      .leia-mais-btn:active {
         border: 2px solid #58b4ae !important; 
         color: #fff !important;
         background-color: #58b4ae !important;
      }

      .background-public{
         background-image: url("{{ asset('imagens/DVIbanner/DVI-banner-public.png') }}");
         background-size: cover; 
         background-position: center; 
         height: 500px; 
         display: flex; 
         align-items: center;  
         justify-content: center;
      }

      .titulo-shining3d{
         font-size:30px;
         color: #000;
         font-family: sans-serif;
         font-weight: bold;
         transition: 1 ease;
      }

      .subTitulo{
         font-size:23px;
         color:  #52918d;
         font-family: sans-serif;
         font-weight:bold;
         letter-spacing: 2px;
         margin-top:20px;
         margin-bottom:20px;
         margin-left: 25px;

      }

      .lead2{
         color: #ccc;
         margin-bottom:150px;
         font-size:20px;
      }

      .fotoVideo{
         height:200px;
         margin-top:50px;
      }
      
      .noticia{
         /*width: 80%;
         height: 90%;*/
         cursor: pointer;
         transition: all 0.25s ease-in-out;
         box-shadow: -7px 7px 25px 0px rgba(0, 0, 0, 0.75); 
      }

      .noticia:hover {
         transform: scale(1.02);
      }

      .espacamentoParceiro{
         margin-block:40px;
      }

      .VideoRecente {
         padding-block: 5rem;
      }

      .tituloVideo{
         font-family: sans-serif;
         font-size: 30px;
         color: gray;
         font-weight: bold;
      }

      .innovation-left {
         display: flex;
         flex-direction: column;
         gap: 16px;
      }

      .innovation-left span {
         font-weight: 700;
      }
      

      @media (min-width:0px) and (max-width: 400px){
         .textoDvi{
            padding-top:20px;
            text-align: center !important;

         }

         .caixaArtigo{
            font-size:25px;
            cursor: pointer;
            text-align:justify !important;
            margin-right: 0px;
            word-spacing: -2px !important;
         }

         .caixaSubArtigo{
            font-size:20px;
            font-weight: 500;
            text-align:justify !important;
            margin-right: 0px;
            word-spacing: 0px !important;
         }

         .noticia{
            margin-top:20px;
         }

         .background-public{
            height:200px;
         }
         
         .leia-mais-btn{
            margin-left:100px;
         }

         .titulo{
            font-size:23px;
            color: #ccc;
            text-align: center !important;
            font-family: sans-serif;
            letter-spacing: 2px;
            margin-top:50px;
            margin-left: 0px;
         }

         .tituloVideo{
            font-family: sans-serif;
            font-size: 30px;
            text-align:center !important;
            color: gray;
            font-weight: bold;
            margin-top:30px;
            margin-bottom: 30px;
         }

      }
      
      @media (min-width: 575px) and (max-width: 700px){
         .titulo{
            font-size:23px;
            color: #ccc;
            text-align: center !important;
            font-family: sans-serif;
            letter-spacing: 2px;
            margin-top:50px;
            margin-left: 0px;
         }
      }

      @media (min-width:400px) and (max-width:575px) {
         .titulo{
            font-size:23px;
            color: #ccc;
            text-align: center !important;
            font-family: sans-serif;
            letter-spacing: 2px;
            margin-top:50px;
            margin-left: 0px;
         }
      }
      

      @media (min-width: 700px) and (max-width: 991.98px){
         .banner-desktop{
            height: max-content;


         }
         .dscore-subtitulo{
            margin-top: 0px !important;
            padding-bottom: 0px !important;

         }
         .img-shining3d{
            margin-bottom: 0px;
            padding-bottom: 0px;
         }
         .caixaArtigo{
            cursor: pointer;
            font-size:25px;
            text-align:justify !important;
            margin-right: 0px;
            word-spacing: -2px !important;
         }

         .caixaSubArtigo{
            font-size:20px;
            text-align:justify !important;
            margin-right: 0px;
            word-spacing: 0px !important;
         }

         .titulo{
            font-size:23px;
            color: #ccc;
            text-align: center !important;
            font-family: sans-serif;
            letter-spacing: 2px;
            margin-top:50px;
            font-weight: bold;
            word-spacing: 2px;
            margin-left: 0px;
         }


         /*.noticia{
            height:80%;
            width:100%;
         }*/

         .caixaArtigo{
            font-size:20px;
            cursor: pointer;
         }

         .caixaSubArtigo{
            font-size:15px;
         }

         .background-public{
            width:100%;
            height:200px;
         }

         .owl-prev {
            position: absolute;
            left: 10px; /* Posição da seta esquerda */
            top: 50%;
            transform: translateY(-1200%) !important;
         }

         .owl-next {
            position: absolute;
            right: 10px; /* Posição da seta direita */
            top: 50%;
            transform: translateY(-1200%) !important;
         }
      }

      @media (min-width: 1024px) and (max-width: 1120px){


         .caixaArtigo{
            cursor: pointer;
            font-size:25px;
            text-align:justify !important;
            margin-right: 0px;
            word-spacing: -2px !important;
         }

         .caixaSubArtigo{
            font-size:20px;
            text-align:justify !important;
            margin-right: 0px;
            word-spacing: 0px !important;
         }

         .titulo{
            font-size:23px;
            color: #ccc;
            text-align: center !important;
            font-family: sans-serif;
            letter-spacing: 2px;
            margin-top:50px;
            margin-left: 0px;
         }

         .owl-prev {
            position: absolute;
            left: 10px; /* Posição da seta esquerda */
            top: 50%;
            transform: translateY(-550%) !important;
         }

         .owl-next {
            position: absolute;
            right: 10px; /* Posição da seta direita */
            top: 50%;
            transform: translateY(-550%) !important;
         }

      }


      #slider3D {
         /* Estilo para o carousel */
      }

      .owl-prev,
      .owl-next {
         font-size: 30px;
         color: black; /* Setas pretas */
         background-color: transparent;
         border: none;
         outline: none;
         transition: 0.5s ease;
      }

      .custom-nav {
         position: relative;
         z-index: 1;
      }

      .owl-prev {
         position: absolute;
         left: 10px; /* Posição da seta esquerda */
         top: 50%;
         transform: translateY(-700%);
      }

      .owl-next {
         position: absolute;
         right: 10px; /* Posição da seta direita */
         top: 50%;
         transform: translateY(-700%);
      }

      .owl-prev:hover,
      .owl-next:hover {
         cursor: pointer;
         color: gray;
      }

      .titulo-grande {
         color: #ec6726;
         font-weight: bold; 
         font-size: 55px;
      }

      .titulo-medio {
         font-weight: bold;
         color: #292d36;
         margin-top: 120px;
         font-size: 35px;
      }

      .titulo-pequeno {
         color: #292d36;
         font-size: 25px;
      }

      .imagem-clinicorp {
         margin-top: 80px;
         border-top-left-radius: 40px;
         border-bottom-right-radius: 40px;
      }

      .teste-borda2 {
         border: 2px solid #fff;
         border-top-left-radius: 15px;
         margin-top: 20px;
         margin-bottom: 20px;
         max-width: 30%;
         margin-left: 50px;
         margin-right: 50px;
         padding: 20px 30px;
         transition: all 0.5s ease-in-out;
      }

      .teste-borda2:hover {
         transform: scale(1.1, 1.1);
         cursor: pointer;
         border-top-left-radius: 15px;
      }

      .teste-borda3 {
         border: 2px solid #fff;
         border-bottom-left-radius: 15px;
         margin-top: 20px;
         margin-bottom: 20px;
         width: 400px;
         padding: 20px 30px;
         transition: all 0.5s ease-in-out;
      }

      .teste-borda3:hover {
         transform: scale(1.1, 1.1);
         cursor: pointer;
         border-bottom-left-radius: 15px;
      }

      .laranjinha {
         position: relative;
         margin-top: 150px;
         margin-left: 80px;
         padding: 0px; /* Adapte conforme necessário para evitar que o conteúdo encoste na borda */
         /* Outros estilos do seu container */

         /* Adicionando o contorno no canto superior esquerdo */
         &:before {
            content: "";
            position: absolute;
            top: -50px; /* Ajuste conforme necessário para compensar a largura da borda */
            left: -50px; /* Ajuste conforme necessário para compensar a largura da borda */
            border-top-left-radius: 35px; /* Raio para a parte superior esquerda, ajuste conforme necessário */
            border: 30px solid ; /* Cor laranja e largura de 15px, ajuste conforme necessário */
            border-color: #ec6726 transparent transparent #ec6726 ;
            width: 100px; /* Largura do canto, ajuste conforme necessário */
            height: 100px; /* Altura do canto, ajuste conforme necessário */
            z-index: 1; /* Certifica-se de que o canto esteja sobre o conteúdo do contêiner */
         }
      }

   
      .btn-fale-especialista{
         background-color: transparent;
         border: 1px solid white;
         padding: 10px 40px 10px 40px;
         color:white;
         font-size: 25px;
         border-top-left-radius: 5px;
         border-bottom-right-radius: 5px;
         border-bottom-left-radius: 5px;
         border-top-right-radius: 5px;
         transition: background-color 0.3s;
         transition: ease 0.5s;
         margin-bottom: 15px;
         z-index: 2;
      }
   
      .btn-fale-especialista:hover{
         background-color: #fff;
         color:#536f81;
      }

      .footer {
         margin-top: unset !important;
      }

      .teste-borda {
         /* box-shadow: 0 1px 3px 0 rgba(60,64,67,0.3); */
         margin-bottom: 10px;
         max-width: 30%;
         /* margin-left:10px; */
         background-color: #e9ecee80;
      }
   

      .leia-mais-btn-shining {
         display: inline-block;
         padding: 10px 20px;
         background-color: transparent;
         color: gray;
         font-family: sans-serif;
         font-weight: 700;
         border-radius: 8px;
         text-decoration: none !important;
         transition: background-color 0.10s;
         transition: ease 0.10s;
         float: left;
         margin-bottom:50px !important;
         border: 2px solid #be9652!important; 
      }
   
      .leia-mais-btn-shining:hover {
         background-color: #be9652;
         color: #fff;
      }

      .leia-mais-btn-clinicorp:active {
         border: 2px solid #de5315 !important; 
         color: #fff !important;
         background-color: #de5315 !important;
      }

      .paragrafo-especialista {
         color: #ffffff; 
         font-size:25px;
         margin-top: -75px;
      }

      .titulo-especialista {
         color: #ffffff; 
         font-size:30px; 
         text-align:left; 
         margin-top: 15px;
      }

      .span-especialista {
         font-weight:bold;
      }


      .expand-all-items {
         transition: all 0.25s ease-in-out;
         cursor: pointer;
      }

      .testeborda.expand-all-items {
         background-color: #E9ECEE66;
      }

      .expand-all-items:hover {
         transform: scale(1.02);
         cursor: pointer;
         box-shadow: none;
      }

      @media (max-width:768px) {
         #videoPlayer {
            width: unset;
            max-height: 70vh;
         }
         .img-shining3d {
            width: 60%;
         }
         .btn-fale-especialista{
            padding: 10px 20px;
            margin-bottom:40px;
            font-size: 14px;
            font-weight: bold;
         }
         .paragrafo-especialista {
            font-size:30px;
            margin-top: -70px;
         }
         .titulo-especialista {
            font-size:20px; 
            margin-top: 0px;
         }
         .img-mobile{
            display: block;
            margin-left: auto;
            margin-right: auto;
         }
         .innovation {
            gap: 4rem;
         }
      }

      .banner-dentisply {
         background-image: url(../imagens/teste/bannertopo-destisply.png);
         background-repeat: no-repeat;
         background-size: contain;
         margin-top:65px;
      }

      .dscore-titulo {
         font-family: "Gotham", sans-serif;
         font-weight: 700;
         font-size: clamp(3.75rem, 8vw, 4.375rem);
         line-height: 1.075em;
         color: #fff;
         float: left;
         width: auto;
         padding-bottom: 0px
      }

      .dscore-subtitulo {
         font-family: "Gotham", sans-serif;
         font-weight: 600;
         font-size: 1.75rem;
         line-height: 2.8125rem;
         color: #ffffff;
         float: left;
         padding-bottom: 20px
      }

      .section-fluxo {
         justify-content: space-around !important;
         padding: 4rem;
      }

      @media (max-width: 640px) {
         .section-fluxo {
            flex-direction: column;
            align-content: center;
         }

         .teste-borda {
            max-width: 75%;
         }
      }

      .img-notebook {
         margin-top: 80px;
      }

      .paragrafo-notebook {
         color: grey; 
         font-family: "Gotham", sans-serif;
         font-size:0.975rem;
      }

      .titulo-notebook {
         color: #000;
         font-weight: bold;
         font-family: "Gotham", sans-serif;
         font-size:1.2rem;
         /* width: 500px; */
         padding-top: 24px;
      }

      @media(max-width:768px) {
         /* .etapa-1 {
            width:350px;
         }
         .etapa-2 {
            width:350px;
         } */

         .melhorando-crescimento {
            font-size: 1.50rem;
            width: 280px;
            font-weight: normal !important;
         }

         .imagem-dentsplymobile {
            margin-left: 120px;
         }
         .mobile-alinhamento{
            margin-top: 0px;
         }

      }

      @media (min-width: 769px) {
         .banner-mobile {
            display: none !important;
         }
      }
      @media (max-width: 1250px){
         .section-fluxo {
            padding: 4rem 0;
         }
      }


      
      /* .etapa-1 {

      }
      .etapa-2 {

      }
      .etapa-3 {
         width:200px
      } */

      .melhorando-crescimento { 
         font-size:2.70rem; 
         font-family: 'Gotham', sans-serif; 
         font-weight: 700; 
         padding-left: 1.10rem;
         color:rgb(230, 230, 230);
      }

      .background-dentsplymeio {
         background: linear-gradient(90deg, rgba(39,67,85,1) 0%, rgba(93,120,139,1) 20%, rgba(83,111,129,1) 80%, rgba(39,67,85,1) 100%);
         padding-right: 0px !important; 
         padding-left: 0px !important;
         padding-top: 50px;
         padding-bottom: 20px; 
      }

      .banner-teste {
         background-image:url(../imagens/teste/sh3d2.png);
         right:700px;
      }

      .img-shining3d{
         width: 40%;
      }

      @media (min-width: 769px) and (max-width: 1300px){

         .img-shining3d{
            /* margin-top: -70px !important; */
         }
      }


      @media (min-width: 1364px) and (max-width: 1440px){

      }

   </style>

   <div style="position: relative;">
      <!-- <img class="img-fluid banner-desktop" src="{{ asset('imagens/teste/sh3d1.png') }}" alt="" style="margin-top: 0px;"> -->
      <video id="videoPlayer" autoplay muted loop pause>
         <source src="{{ asset('imagens/teste/Banner_video_shining3d.mp4') }}" type="video/mp4">
      </video>
      <div class="container banner-desktop" style="padding:unset;">
         <div class="row text-start" style="flex-direction: column;">
            <img class="img-fluid expand-all-items img-shining3d" src="{{ asset('imagens/teste/dentalweb.png') }}" alt="">
         
            <h2 class="dscore-subtitulo">Fluxo Digital Completo do Escaneamento à Impressão 3D </h2>

            <button class="shining-button" style="margin-inline: 12px;"><a href="https://share.hsforms.com/1EaSHF3aRQCG3_Ox5ysgN5Qdi57l?utm_campaign=linkinbio&utm_medium=referral&utm_source=later-linkinbio&fbclid=PARlRTSAQngE9leHRuA2FlbQIxMQBzcnRjBmFwcF9pZA8xMjQwMjQ1NzQyODc0MTQAAaceb-uIybg9j5UFp_4SnxwxgbiM3TLrL1NQscK3lOBnsDEOSPe8zc0no_H79g_aem_Dqz6AuIi7kdPgsy34QAS2w">Fale com um especialista</a></button>
         </div>
      </div>      
   </div>
   <!-- <div class="container-fluid banner-mobile banner-teste" >
      <div class="container">
         <div class="row text-start" style="margin-top:00px;">
            <a href="https://www.shining3ddental.com/pt/"><img class="img-fluid expand-all-items" style="margin-top:80px; margin-bottom: 30px;" src="{{ asset('imagens/teste/dentalweb.png') }}" alt=""></a>
            <h2 class="dscore-subtitulo" style="font-size:20px; line-height: 30px">Fluxo Digital Completo Do Escaneamento a Impressão 3D</h2>
         </div>
      </div>
   </div> -->

   <div class="container-fluid" style="background-color: #ffffff; margin-top: -6px;">

      <div class="row justify-content-center section-fluxo">
         
         <div class="col-lg-4 col-md-4 teste-borda expand-all-items" style="border-radius: 18px; padding: 24px;">
          <a href="https://www.shining3ddental.com/pt/" style="text-decoration: none;">
            <img class="img-fluid img-notebook" src="{{ asset('imagens/teste/scannershining.png') }}" alt="" style="margin-top: unset;">
            <h1 class="titulo-notebook  etapa-1">Scanner Intraoral</h1>
            <p class="paragrafo-notebook">Oferecemos uma variedade de scanners, com ou sem fio, adequados para diferentes aplicações. Nossos dispositivos de qualidade contam com software integrado, sem custos de mensalidade, e ponteiras autoclaváveis em vários tamanhos, inclusive para uso pediátrico. <br>Isso garante digitalização precisa para múltiplas finalidades, como restaurações completas, coroas, placas e alinhadores. <br>O modelo Aoralscan Elite possui Fotogrametria Intraoral, uma tecnologia exclusiva da SHINING 3D Dental, desenvolvida especialmente para pacientes edêntulos e considerada o padrão-ouro em precisão na Odontologia.</p>
            </a>
         </div>
      
         <div class="col-lg-4 col-md-4 teste-borda expand-all-items" style="border-radius: 18px; padding: 24px;">
            <a href="https://www.shining3ddental.com/pt/" style="text-decoration: none;">
            <img class="img-fluid img-notebook" src="{{ asset('imagens/teste/metismile.png') }}" alt="" style="margin-top: unset;">
            <h1 class="titulo-notebook  etapa-2">Scanner Facial</h1>
            <p class="paragrafo-notebook">O MetiSmile é um scanner facial 3D desenvolvido exclusivamente para Odontologia, permitindo captura rápida e precisa de dados faciais. Realiza escaneamento completo em cerca de 10 segundos, com precisão de até 50 micrômetros, usando câmeras de alta resolução. <br>Possui tecnologia infravermelha segura para os olhos e pode ser usado em modo fixo ou portátil, pesando apenas 800 gramas. <br>Integra de forma totalmente automática os dados faciais com escaneamentos intraorais, criando um paciente virtual. Exporta os arquivos STL, OBJ e PLY, garantindo compatibilidade com outros softwares e fluxos digitais.</p>
             </a>
         </div>
         <div class="col-lg-4 col-md-4 teste-borda expand-all-items" style="border-radius: 18px; padding: 24px;">
            <a href="https://www.shining3ddental.com/pt/" style="text-decoration: none;">
            <img class="img-fluid img-notebook" src="{{ asset('imagens/teste/scanner3d.png') }}" alt="" style="margin-top: unset;">
            <h1 class="titulo-notebook etapa-3">Impressora 3D</h1>
            <p class="paragrafo-notebook">Nossas impressoras 3D são soluções avançadas para a Odontologia Digital, oferecendo precisão, velocidade e integração com fluxos clínicos e laboratoriais. Possuímos diversos modelos, cada um com especificações adaptadas a diferentes necessidades. <br>Os equipamentos se destacam pela precisão, compatibilidade com múltiplos materiais, integração digital e praticidade. <br>A linha AccuFab oferece soluções de qualidade para a produção de modelos, guias cirúrgicas, coroas e próteses. Além disso, temos um portfólio completo de resinas e materiais compatíveis com diferentes sistemas e aplicações.</p>
            </a>
         </div>
      </div>
   </div>
   <!-- <div class="container-fluid background-dentsplymeio" style="background: linear-gradient(90deg, rgba(118, 165, 194) 0%,rgb(72, 94, 142) 20%, rgb(118, 165, 194) 80%, #000e2d 100%);">
      <div class="container" style="padding-bottom:14px;">
         <div class="row flex-column-sm">
            <div class="col-md-8 order-md-0 order-sm-1 text-md-start mobile-alinhamento">
               <p class="melhorando-crescimento">Transformando o Futuro da Odontologia</p>
            </div>
            <div class="col-md-4 order-md-1 order-sm-0 text-md-end mobile-alinhamento">
               <a href="https://www.shining3ddental.com/pt/"  target="_blank"><img class="expand-all-items imagem-dentsplymobile mt-3" src="{{ asset('imagens/teste/dentalweb.png') }}" alt="" style="width: 60%;"></a>
            </div>
         </div>
      </div>
   </div> -->



   <!-- <div style="background-color: #ffffff;">
      <div class="container" style="padding-block: 4rem;">
         <div class="row">
            <div class="col-lg-7 col-md-7">
            <a href="https://www.youtube.com/watch?v=--eXZ8DDnFg&list=PLnJw9Q8I9q8fjnXILbx3AA3N9TvxGidY4" style="text-decoration:none" target="_blank"><h4 class="titulo-shining3d">Confira nosso último evento</h4></a>
               <h4 class="subTitulo"><span style="font-size:25px; font-weight:800">Rio Embracing Innovation</span><br>que reuniu parceiros, distribuidores e <br>especialistas de toda a América Latina.</h4>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 text-center" style="padding-top: 50px; margin-bottom:40px">
               <iframe width="100%" height="315" src="https://www.youtube.com/embed/--eXZ8DDnFg?si=ya1BMT1VCijIzpSh" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
         </div>
      </div>
   </div> -->



   <div style="background-color: #e9ecee;">
      <div class="container d-none d-sm-none d-md-block d-lg-block d-xl-block d-xxl-block " style="padding-block: 4rem 2rem;">
         <div class="row">
           <!-- <h1 class="tituloVideo">ÚLTIMAS NOTICIAS</h1> -->
            <div class="container">
               <div class="slide espacamentoParceiro">
                  <div class="row">
                     @foreach($materias[0]->productItems as $index => $materia)
                         <div class="col-12 col-lg-6 col-md-8">
                             <h3 class="caixaArtigo" data-bs-toggle="modal" data-bs-target="#leiaCapitulo{{$materia->id}}">{{$materia->title}}</h3>
                             <p class="caixaSubArtigo" >
                                 {{ limita_caracteres(strip_tags( strip_tags($materia->contentText) ), 300, false) }}
                             </p>
                             <button type="button" class="btn leia-mais-btn-shining" data-bs-toggle="modal" data-bs-target="#leiaCapitulo{{$materia->id}}">Leia mais</button>     
                         </div>        
                         <div class="col-12 col-lg-6 col-md-4">
                             <img src="{{$materia->cover}}"  alt="{{$materia->title}}" class="img-fluid noticia" data-bs-toggle="modal" data-bs-target="#leiaCapitulo{{$materia->id}}">
                         </div>      
                         @if (! $loop->last)
                             <div class="boxbordas"></div>
                         @endif
                         <!-- Modal do artigo -->
                        <div class="modal fade" id="leiaCapitulo{{$materia->id}}" tabindex="-1" aria-labelledby="leiaCapitulo{{$materia->id}}" aria-hidden="true">
                          <div class="modal-dialog" style="--bs-modal-width: 1024px;">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="leiaCapitulo{{$materia->id}}">{{ $materia->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                              </div>
                              <div class="modal-body" style="text-align: left;">
                                @php
                                 echo $materia->contentText;
                                @endphp
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__("messages.RevistaBladeFechar")}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                     @endforeach
                  </div>    
               </div>
            </div>
         </div>
      </div>
   </div>

 <!-- MOBILE DA MATERIAS LOGO ABAIXO: -->

   <div class="container d-sm-block d-md-none d-lg-none d-xl-none d-xxl-none">
      <div class="row">
        <!-- <h1 class="tituloVideo">ÚLTIMAS NOTICIAS</h1> -->
         <div class="container">
            <div class="slide espacamentoParceiro">
               <div class="row">
                  @foreach($materias[0]->productItems as $index => $materia)
                      <div class="col-12 col-lg-6 col-md-4 mb-5">
                          <img src="{{$materia->cover}}"  alt="{{$materia->title}}" class="img-fluid noticia" data-bs-toggle="modal" data-bs-target="#leiaCapitulo{{$materia->id}}">
                      </div>  
                      <div class="col-6"></div>
                      <div class="col-12 col-lg-6 col-md-8">
                          <h3 class="caixaArtigo" data-bs-toggle="modal" data-bs-target="#leiaCapitulo{{$materia->id}}">{{$materia->title}}</h3>
                          <p class="caixaSubArtigo" >
                              {{ limita_caracteres(strip_tags( strip_tags($materia->contentText) ), 300, false) }}
                          </p>
                          <button type="button" class="btn leia-mais-btn-shining" data-bs-toggle="modal" data-bs-target="#leiaCapitulo{{$materia->id}}">Leia mais</button>     
                      </div>        
    
                      @if (! $loop->last)
                          <div class="boxbordas"></div>
                      @endif
                      <!-- Modal do artigo -->
                     <div class="modal fade" id="leiaCapitulo{{$materia->id}}" tabindex="-1" aria-labelledby="leiaCapitulo{{$materia->id}}" aria-hidden="true">
                       <div class="modal-dialog" style="--bs-modal-width: 1024px;">
                         <div class="modal-content">
                           <div class="modal-header">
                             <h5 class="modal-title" id="leiaCapitulo{{$materia->id}}">{{ $materia->title }}</h5>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                           </div>
                           <div class="modal-body" style="text-align: left;">
                             @php
                              echo $materia->contentText;
                             @endphp
                           </div>
                           <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__("messages.RevistaBladeFechar")}}</button>
                           </div>
                         </div>
                       </div>
                     </div>
                  @endforeach
               </div>    
            </div>
         </div>
      </div>
   </div>

   

   <!-- <div class="container-fluid" style="background: linear-gradient(90deg, rgba(118, 165, 194) 0%,rgb(72, 94, 142) 20%, rgb(118, 165, 194) 80%, #000e2d 100%); min-height: 250px;">
      <div class="container">
         <div class="row">
               <a href="https://www.shining3ddental.com/pt/"><img class="img-fluid expand-all-items img-mobile" style="margin-top:40px; z-index: 1; width:0%;" src="{{ asset('imagens/teste/shininglogoredondo.png') }}" alt=""></a>
            <div class="col-12 text-center" style="margin-top: 80px;">
               <p class="paragrafo-especialista text-center">
                  Soluções integradas para todas as etapas do fluxo clínico
               </p>
               <a href="https://www.wa.link/13xxit" target="_blank"><button class="btn-fale-especialista">Fale com um Especialista</button></a>
               <p style="font-size: 30px; color:white; margin-bottom:30px"></p>
            </div>
         </div>

     </div>
   </div> -->

   <div style="background-color: #fff;">
      <div class="container VideoRecente">
         <div class="col-sm-12">
            <div class="row">
               <h3 class="tituloVideo">{{__("messages.TopoMenuVideos")}}<a href="https://www.youtube.com/@SHINING3DDental" target="_blank" style="color:gray; text-decoration: none;"><small style="font-size: 13px; margin-left: 30px;"> {{__("messages.HomeBladeVeja")}} <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></small></a></h3>
            </div>
         </div>
         <div class="col-12">
            <div class="row">
               @foreach($videos[0]->productItems as $index => $video)
                  <?php
                     if (empty($video->content)) {
                         $hashVideo = '';
                     }else{
                         $hashVideo = explode('/', $video->content);
                         $hashVideo = $hashVideo[2];
                     }
                     
                  ?>
               <div class="col-md-2"></div>
                  <div class="col-12 col-md-8 text-center mt-3" style="margin-bottom: 15px;">
                     <!-- <button id="VideoId{{$video->id}}" class="video-btn videoButaum" data-bs-toggle="modal" data-bs-target="#modalVideo" data-src="{{ $hashVideo }}">
                        <img src="{{ $video->cover }}" class="videoImagem" alt="{{ $video->title }}">
                        <label style="display: none;">
                           {{ $video->title }}
                        </label>
                     </button> -->
                     <iframe width="100%" height="315" src="https://www.youtube.com/embed/iqUSRgZVKjw?si=CVBm_OTAe_VIOr_d" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                  </div>
               <div class="col-md-2"></div>
               @endforeach
            </div>
            
         </div>


         <!--<div class="container-fluid">
            <section id="slider3D">      
               <div class="slider">
                  <div class="owl-carousel owl-carousel-cinco">
                     <div class="slider-card">
                        <div class="d-flex justify-content-center align-items-center">
                           <div class="item"><img src="{{ asset('imagens/DVIbanner/DVI1.jpg') }}"></div>
                        </div>
                     </div>   
                     <div class="slider-card">   
                        <div class="d-flex justify-content-center align-items-center">   
                           <div class="item"><img src="{{ asset('imagens/DVIbanner/DviCarousel2.jpg') }}"></div>
                        </div>
                     </div>
                     <div class="slider-card">   
                        <div class="d-flex justify-content-center align-items-center">   
                           <div class="item"><img src="{{ asset('imagens/DVIbanner/DviCarousel3.jpg') }}"></div>
                        </div>   
                     </div> 
                  </div>
               </div>
            </section>
   
            <div class="custom-nav">
               <button class="carousel-custom-prev owl-prev" type="button">
                  <i class="fa fa-chevron-left"></i>
               </button>
   
               <button class="carousel-custom-next owl-next" type="button">
                  <i class="fa fa-chevron-right"></i>
               </button>
            </div>
   
   
         </div>-->
      </div>
   </div>

   <div style="background-color: #e9ecee;">
      <div class="container" style="padding-block: 4rem;">
         <div class="row innovation" style="padding-block: 6rem;">
            <div class="col-lg-6 col-md-6 innovation-left">
               <h4 class="titulo-shining3d">SHINING 3D Dental Inovação em Odontologia Digital</h4>
               <p>Desenvolvemos <span>soluções</span> para todas as etapas do <span>fluxo clínico</span>, do <span>escaneamento</span> intraoral e facial à <span>impressão 3D</span>. Nossos dispositivos oferecem <span>recursos avançados</span> e aplicações diversas, com software integrado que <span>otimiza</span> todo o processo.</p>
               <button class="shining-button"><a href="https://share.hsforms.com/1EaSHF3aRQCG3_Ox5ysgN5Qdi57l?utm_campaign=linkinbio&utm_medium=referral&utm_source=later-linkinbio&fbclid=PARlRTSAQngE9leHRuA2FlbQIxMQBzcnRjBmFwcF9pZA8xMjQwMjQ1NzQyODc0MTQAAaceb-uIybg9j5UFp_4SnxwxgbiM3TLrL1NQscK3lOBnsDEOSPe8zc0no_H79g_aem_Dqz6AuIi7kdPgsy34QAS2w">Fale com um especialista</a></button>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 text-center innovation-right">
               <img class="img-fluid" src="{{ asset('imagens/shining3d-circle-devices.png') }}">
            </div>
         </div>
      </div>
   </div>

<div class="modal fade" id="modalVideo" tabindex="-1" aria-labelledby="modalVideoLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modalCentraliza">
     <div class="modal-content modalRedondinho" style="background: transparent; border: 0;">
       <div class="modal-body">
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: -25px; color: #fff; border: 2px solid #1a1a1a;"></button>
           <iframe class="embed-responsive-item" src="{{ route('loadingvideo') }}" id="video"  allowscriptaccess="always" allow="autoplay" width="100%" height="450px" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen="" data-ready="true" loading="lazy"></iframe>
       </div>
     </div>
   </div>
</div>
<style type="text/css">

    #modalVideo .modal-dialog {
      max-width: 800px;
      margin: 30px auto;
    }

    #modalVideo .modal-body {
      position:relative;
      padding:0px;
    }
    #modalVideo .close {
      position:absolute;
      right:-30px;
      top:0;
      z-index:999;
      font-size:2rem;
      font-weight: normal;
      color:#fff;
      opacity:1;
    }

</style>

@endsection