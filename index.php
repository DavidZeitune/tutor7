<?php
require_once 'init.php';
$theme  = $ini['general']['theme'];
new TSession;

if (!empty($_REQUEST['theme']))
{
    TSession::setValue('theme', $_REQUEST['theme']);
}
if (!empty(TSession::getValue('theme')))
{
    $theme = TSession::getValue('theme');
}

$menu_string = AdiantiMenuBuilder::parse('menu-slim.xml', $theme);

$content  = file_get_contents("app/templates/{$theme}/layout.html");
$content  = ApplicationTranslator::translateTemplate($content);
$content  = str_replace('{LIBRARIES}', file_get_contents("app/templates/{$theme}/libraries.html"), $content);
$content  = str_replace('{class}', isset($_REQUEST['class']) ? $_REQUEST['class'] : '', $content);
$content  = str_replace('{template}', $theme, $content);
$content  = str_replace('{MENU}', $menu_string, $content);
$content  = str_replace('{username}', 'User name here', $content);
$content  = str_replace('{usermail}', 'user@mail', $content);
$css      = TPage::getLoadedCSS();
$js       = TPage::getLoadedJS();
$content  = str_replace('{HEAD}', $css.$js, $content);

echo $content;

if (isset($_REQUEST['class']))
{
    $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : NULL;
    AdiantiCoreApplication::loadPage($_REQUEST['class'], $method, $_REQUEST);
}
