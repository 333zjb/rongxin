<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/30
 * Time: 上午9:45
 */
// 检测PHP环境
//if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');
//报告所有错误
error_reporting(E_ALL);
//自动加载文件
//时区
date_default_timezone_set('Asia/Shanghai');
//定义常量
define('APP_PATH', __DIR__ . '/application/');
define('BASE_PATH', __DIR__ . '/base/');
define('UPLOAD_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
define('EXT', '.php');

//自动加载类
function auto_model_class($class_b)
{
    $class = strtolower($class_b);
    $paths = array('model/', 'service/');
    foreach ($paths as $path) {
        $tmp_file = APP_PATH . $path . $class;
        if (is_file($tmp_file . EXT)) {
            require $tmp_file . EXT;
            return;
        }
    }
}

spl_autoload_register('auto_model_class');

//手动加载必要文件
require __DIR__ . '/vendor/autoload.php';
require BASE_PATH . 'base_controller.php';
require BASE_PATH . 'base_model.php';
require APP_PATH . 'helper/base_helper.php';

//路由实现
$request_param = array(
    'divide_group' => 'api',
    'controller' => 'index',
    'method' => 'login'
);
if(isset($_SERVER['REQUEST_URI'])) {
    $divide_group = array('admin', 'api');//分组

    //为了兼容windows
    $file_match = str_replace('\\', '/', __FILE__);
    $dir_match = str_replace('\\', '/', __DIR__);

    $index_php = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_match);

    $has_get = strpos($_SERVER['REQUEST_URI'], '?');
    $has_get_bool = ($has_get !== false);
    $tmp_request_uri = $has_get_bool ? substr($_SERVER['REQUEST_URI'], 0, $has_get) : $_SERVER['REQUEST_URI'];
    $get_index = trim($tmp_request_uri, '/');
    $get_index = str_replace('index.php', 'index_php', $get_index);
    unset($_GET[$get_index]);

    $behind_string = str_replace($index_php, '', $tmp_request_uri);
    $behind_string = trim($behind_string, '/');
    $behind_array = $behind_string === '' ? array() : explode('/', $behind_string);
    //如果有项目名的话，去除项目名
    if($_SERVER['DOCUMENT_ROOT'] !== $dir_match){
        $project_name = str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $dir_match);
        if($project_name === $behind_array[0]){
            array_shift($behind_array);
        }
    }
    $behind_array_length = count($behind_array);

    if ($behind_array_length > 0) {
        $had_divide_group = in_array($behind_array[0], $divide_group);
        $request_param['divide_group'] = $had_divide_group ? $behind_array[0] : $request_param['divide_group'];

        if ($behind_array_length > 1) {
            $request_param['controller'] = $had_divide_group ? $behind_array[1] : $behind_array[0];
        } else {
            $request_param['controller'] = $had_divide_group ? $request_param['controller'] : $behind_array[0];
        }

        if ($behind_array_length > 2) {
            $request_param['method'] = $had_divide_group ? $behind_array[2] : $behind_array[1];
        } else {
            $tmp_method = isset($behind_array[1]) ? $behind_array[1] : $request_param['method'];
            $request_param['method'] = $had_divide_group ? $request_param['method'] : $tmp_method;
        }
    }
}else{
    $request_param['divide_group'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : $request_param['divide_group'];
    $request_param['controller'] = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : $request_param['controller'];
    $request_param['method'] = isset($_SERVER['argv'][3]) ? $_SERVER['argv'][3] : $request_param['method'];
}
$request_class_path = APP_PATH . $request_param['divide_group'] . '/' . $request_param['controller'] . EXT;
if (file_exists($request_class_path)) {
    require $request_class_path;
    $request_controller = new $request_param['controller']();
    $tmp_method = $request_param['method'];
    if (method_exists($request_controller, $tmp_method) && is_callable(array($request_controller, $tmp_method))) {
        $request_controller->$tmp_method();
    } else {
        include __DIR__ . '/error_404.html';
    }
} else {
    include __DIR__ . '/error_404.html';
}