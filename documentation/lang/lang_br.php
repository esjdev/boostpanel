<?php

$lang['lang_title'] = 'Documentação';
$lang['lang_last_updated'] = 'Ultima atualização';
$lang['lang_description'] = 'Guia de Documentação BoostPanel';
$lang['lang_recommended_https'] = 'É altamente recomendável usar <b>HTTPS</b> para uma melhor experiência com o <b>BoostPanel</b>.';

$lang['lang_see_screen'] = 'Você verá esta tela';
$lang['lang_note'] = 'Obs';

$lang['lang_introduction'] = 'Introdução';
$lang['lang_subintroduction'] = 'O <b>BoostPanel</b> é uma <b>ferramenta de marketing de mídia social</b> on-line (aplicativo da Web) que permite e ajuda você a vender seus Serviços de Marketing de Mídia Social com um painel fácil de usar. Você pode fornecer serviços SMM baratos e de qualidade aos seus clientes, eles podem comprar todos os pacotes ou serviços como curtidas no <b>Facebook, seguidores do Instagram, seguidores do Twitter, visualizadores do YouTube, SEO</b> e muito mais. Você pode criar quantos serviços e pacotes de acordo com sua experiência, este é um painel completamente dinâmico. Seja algo que você precisa para suas contas de mídia social ou seja um revendedor de serviços SMM, você encontrará tudo aqui.';

$lang['lang_installation'] = 'Instalação';

$lang['lang_cronjob_how_access_token'] = '
<strong>Após a instalação, efetue login com sua conta de administrador no painel.</strong><br>
Acesse o menu <b>Admin > Settings</b> e copie o <b>Token</b> no <b>Cron Job Token</b> como na imagem abaixo.
';
$lang['lang_note_cronjob'] = 'Clicar em %s você atualiza o token para um novo, lembrando também que você deve atualizar o token dos links já criados no cron para continuar trabalhando.';
$lang['lang_links_cronjob'] = 'Links para adicionar ao CronJob';
$lang['lang_example_links_cronjob'] = 'Adicione todos os links no cronjob, conforme mostrado no exemplo abaixo. Repita para todos os links.';
$lang['lang_your_token_cronjob'] = 'seu_token_gerado';

$lang['lang_requirements'] = 'Requisitos';
$lang['lang_installation'] = 'Instalação';
$lang['lang_start_installation'] = 'Iniciar Instalação';
$lang['lang_step_installation'] = '
Guias passo a passo para configurar esse script no seu aplicativo da web. Por favor, leia atentamente o seguinte guia.<br><br>

<b>Etapa 1</b> - Carregar e extrair um arquivo zip<br>
Carregue o arquivo zip instalado na sua hospedagem na web. E depois extraia o arquivo zip<br><br>

<b>Etapa 2</b> - Vá para a página de instalação<br>
Abra seu navegador e vá para a página de instalação <code>www.seudominio.com</code><br><br>

<b>Etapa 3</b> - Preencha todas as informações solicitadas.<br>
Após o redirecionamento para a página de instalação, você deve preencher o formulário com todas as informações solicitadas (nome do banco de dados, detalhes da conta do administrador... etc)<br><br>

<b>Etapa 4</b> - Concluir a instalação<br>
Após preencher todas as informações solicitadas na etapa 3. Você verá a mensagem com sucesso após alguns segundos, se tudo der certo.<br><br>
';
$lang['lang_configure_cronjob'] = 'Configurar Cronjob';
$lang['lang_subcronjob'] = 'Para poder enviar um pedido, status do pedido. A tarefa Cron deve ser configurada em sua hospedagem.';
$lang['lang_api_providers'] = 'Provedores de API';
$lang['lang_how_using_api_third'] = 'Como trabalhar usando a API de terceiros no sistema.';
$lang['lang_go_menu_admin_api_providers'] = 'Vá para o menu <b>Admin > API Providers</b>, depois de acessa-lo, você verá isso.';
$lang['lang_note_api_providers'] = '
* Se você excluir uma API, todos os serviços dessa API serão excluídos automaticamente do sistema<br>
* Você pode adicionar quantas APIs desejar<br>
* Suporta dois tipos de parâmetros de API (key e api_token)<br>
* Não é possível adicionar a mesma API duas vezes com a mesma API Key
';
$lang['lang_add_api_provider'] = 'Adicionar provedor de API';
$lang['lang_congratulations_text'] = 'Parabéns, você chegou até aqui!';
$lang['lang_note_success_api_provider'] = 'Se você escolher o tipo de parâmetro da sua API incorretamente, isso causará um erro.';
$lang['lang_subgooglerecaptcha'] = 'Como configurar o Google reCAPTCHA V2';
$lang['lang_access_google_recaptcha'] = '
Acesse o link <a href="%s" target="_blank" class="font-weight-bold text-danger">clicando aqui</a> e, em seguida, clique em <b>Admin console</b>, como mostra a imagem abaixo.';
$lang['lang_fill_out_form_img_google_recaptcha'] = 'Preencha o formulário como na imagem abaixo';
$lang['lang_note_google_recaptcha'] = '
* Recomendamos o uso do <b>Google reCAPTCHA V2</b><br>
* reCAPTCHA ativo na página Login, Registrar e Recuperar Senha
';
$lang['lang_after_click_submit'] = 'Depois de clicar em <b class="text-danger">SUBMIT</b>, você verá esta tela com os dados <b class="text-danger">SITE KEY</b> e <b class="text-danger">SECRET KEY</b>, conforme mostrado abaixo na imagem abaixo';
$lang['lang_access_menu_settings_recaptcha'] = '
Vá para o menu <b>Admin > Settings</b> e clique em <b>Google Recaptcha</b><br>

Clique no botão de status para liberar a configuração de recaptcha do Google, como na imagem abaixo
';
$lang['lang_finally_config_recaptcha'] = 'Agora basta preencher os campos "<b>Key Public</b>" e "<b>Private Key</b>" com as "<b>Site Key</b>" e "<b>Secret Key</b>" que o Google gerou para você e <b>clique em Salvar</b>.';

$lang['lang_congratulations'] = 'Parabéns';

// Files Boostpanel
$lang['lang_files_boostpanel'] = 'Arquivos Boostpanel';
$lang['lang_files'] = '
<strong>Todos arquivos do Boostpanel são de livre uso.</strong><br><br>

Tema Boostpanel Default (<a href="https://colorlib.com" target="_blank">https://colorlib.com</a>)<br>
Boostrap v4.3.1 (<a href="https://getbootstrap.com/docs/4.3/getting-started/introduction/" target="_blank">https://getbootstrap.com/docs/4.3/getting-started/introduction/</a>)<br>
Boostrap DatePicker v1.7.1 (<a href="https://github.com/uxsolutions/bootstrap-datepicker" target="_blank">https://github.com/uxsolutions/bootstrap-datepicker</a>)<br>
Boostrap Toggle v2.2.0 (<a href="https://www.bootstraptoggle.com/" target="_blank">https://www.bootstraptoggle.com/</a>)<br>
Font Awesome v4.5.0 (<a href="https://fontawesome.com/" target="_blank">https://fontawesome.com/</a>)<br>
SweetAlert2 v9.x (<a href="https://sweetalert2.github.io/" target="_blank">https://sweetalert2.github.io/</a>)<br>
c3Chart v0.7.15 (<a href="https://c3js.org/" target="_blank">https://c3js.org/</a>)<br>
ckEditor v4.13.1 (<a href="https://ckeditor.com/ckeditor-4/" target="_blank">https://ckeditor.com/ckeditor-4/</a>)<br>
codeMirror v5.49.2 (<a href="https://codemirror.net/" target="_blank">https://codemirror.net/</a>)<br>
AOS Animate v2.3.1 (<a href="https://michalsnik.github.io/aos/" target="_blank">https://michalsnik.github.io/aos/</a>)<br>
jQuery v3.4.1 (<a href="https://jquery.com/" target="_blank">https://jquery.com/</a>)<br>
jQuery CounterUP v1.0 (<a href="https://github.com/bfintal/Counter-Up" target="_blank">https://github.com/bfintal/Counter-Up</a>)<br>
jQuery tagsInput Revisited v2.1 (<a href="https://github.com/underovsky/jquery-tagsinput-revisited" target="_blank">https://github.com/underovsky/jquery-tagsinput-revisited</a>)<br><br>

Todos os arquivos principais do BoostPanel para o style e scripts Javascript todos eles são minificados e não minificados para edição.
';
