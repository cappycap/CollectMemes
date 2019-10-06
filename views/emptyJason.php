<?php
header('Content-Type: application/json');

require 'base.php';

// Define response array for delivering status.
$jason = array();

  $app = array();

    $head = array();

      // Get this from somewhere!
      /*$data = "none"; */

      $head['data'] = $data;

      /*
      $actions = array();

        // Actions on view loading for the first time.
        $actions['$load'] = array();

        // Actions on view whenever view appears.
        $actions['$show'] = array();

        // Actions on pull down.
        $actions['$pull'] = array();

        // Actions on app returning from background state.
        $actions['$foreground'] = array();

      $head['actions'] = $actions;
      */

      $templates = array();

      $header = array(
        "style"=>array(
          "shy"=>"true"),
        "title"=>array(
          "type"=>"image",
          "style"=>array(
            "width"=>"94",
            "height"=>"27"),
          "url"=>"https://collectmemes.com/docs/assets/images/demo/logo-mask.png"),
          "menu"=>array(
            "image"=>"https://collectmemes.com/menu.png"));

      $footer = array(
        "tabs"=>array(
          "style"=>array(
            "background"=>"rgba(255,255,255,0.8)",
            "color"=>"#000000"),
            "items"=>array(
              array(
                "image"=>"",
                "style"=>array(
                  "height"=>"21"),
                "url"=>"https://collectmemes.com/views/friends.php"),
              array("image"=>"",
                "style"=>array(
                  "height"=>"21"),
                "url"=>"https://collectmemes.com/views/collect.php"),
              array("image"=>"",
                "style"=>array(
                  "height"=>"21"),
                "url"=>"https://collectmemes.com/views/vault.php"))));

      $templateBody = array("header"=>$header,"footer"=>$footer);

      $templates['body'] = $templateBody;

      $head['templates'] = $templates;

    $app['head'] = $head;

  $jason['$jason'] = $app;

echo json_encode($jason, JSON_UNESCAPED_SLASHES);

?>
