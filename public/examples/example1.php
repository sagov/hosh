<?php
require_once dirName(__FILE__) . '/../../hosh/factory.php';
HoshFactory::run();

$xmlfile = __DIR__ . '/form/example1.xml';

require_once 'Hosh/Form.php';
$config = array(
        'pattern' => array(
                'adapter' => 'Xml',
                'data' => $xmlfile
        ),
        'decoratorHelper' => 'Decorator_Form_Bootstrap',
        'decoratorElementHelper' => 'Decorator_Element_Bootstrap'
);
$form = new Hosh_Form('example1', $config);
$form->initialize();
$form->run();
$xhtml_form = $form->render();

$view = Hosh_View::getInstance();
$view->Bootstrap();
$view->AddStyleSheet('https://fonts.googleapis.com/css?family=Russo+One');
$view->AddStyleSheet(
        'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,400italic&subset=latin,cyrillic');
$view->Font_Fontawesome();
$view->AddStyleSheet('/manager/layouts/default/assets/css/stylesheet.css');
$view->Syntaxhighlighter()->AddBrush('php')->AddBrush('xml');
$view->AddScriptDeclaration('SyntaxHighlighter.all();');
?>
<html>
<head>
<title>Example 1 &mdash; Вывод Формы через Xml паттерн</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php echo $view->headLink()?>
<?php echo $view->headScript()?>
</head>
<body>

	<div class="header">
		<div class="header-wrap">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="logo">Hosh Example</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="container">	
		    <div class="page-header"><h1>Пример 1 &mdash; Вывод Формы через Xml паттерн</h1></div>		
	        <?php echo $xhtml_form; ?>
	        
	        <div class="page-header"><h3>PHP</h3></div>
	        <pre class="brush: php;">
// Инициализация фрэймворка
require_once dirName(__FILE__) . '/../../hosh/factory.php';
HoshFactory::run();

...

// Инициализация формы
require_once 'Hosh/Form.php';
$config = array(
        'pattern' => array(
                'adapter' => 'Xml',
                'data' => __DIR__ . '/form/example1.xml'
        ),
        'decoratorHelper' => 'Decorator_Form_Bootstrap',
        'decoratorElementHelper' => 'Decorator_Element_Bootstrap'
);
$form = new Hosh_Form('example1', $config);
$form->initialize();
$form->run();

// Отрисовка формы
$xhtml_form = $form->render();
echo $xhtml_form;</pre>

<div class="page-header"><h3>XML</h3></div>
<pre class="brush: xml;">
<?php 
echo file_get_contents($xmlfile);
?>
</pre>		
		</div>
	</div>
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-sm-push-6">
					<div class="footer-menu"></div>
				</div>
				<div class="col-sm-6 col-sm-pull-6">
					<span class="logo">Hosh &copy; <?php echo date('Y');?></span>
				</div>
			</div>
		</div>
	</div>


</body>
</html>