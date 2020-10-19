<?php
include dirname(__FILE__,2) . '/php-app/' . 'AssetManager.php';

$asset_manager = new VueInLegacyPhp\Templating\AssetManager('demo');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vue.js components as (Webpacked) Library in legacy PHP Applications</title>
    <?php if($asset_manager->css_file_path) :?>
        <link rel="stylesheet" href="<?php echo $asset_manager->css_file_path?>" />
    <?php endif ?>
</head>
<body>
<div id="app">
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <?php
    if($asset_manager->is_production) :
        if(!$asset_manager->has_assets_map) :
            echo 'You must run the build script as the assets folder is currently empty.';

        elseif(empty($asset_manager->js_file_path)) :
            echo 'Something is mis-configured. Maybe the asset name param?';
        endif;
    else :
        echo 'Is the dev server running? If not, cd into "vue-app" dir and run "npm run dev"' ;
        ?>
        <br>
        <a href="http://localhost:8080/webpack-dev-server">Check the dev server output files to troubleshoot</a>
    <?php
    endif; ?>
</div>

<?php if($asset_manager->js_file_path) :?>
    <script src="<?php echo $asset_manager->js_file_path;?>"></script>
    <script>MyVueLib.demo.hello('#app');</script>
<?php endif ?>

</body>
</html>
