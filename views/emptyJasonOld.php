<?php
header('Content-type:application/json;charset=utf-8');

// Define response array for delivering status.
$jason = array();

  $app = array();

    $head = array();

      // Get this from somewhere!
      $data = array("names"=>array(array("type"=>"people","items"=>array(array("name"=>"foo"),array("name"=>"bah"),array("name"=>"zee")))));

      $head['data'] = $data;

      $head['title'] = "Empty";


      $actions = array();

        // Actions on app returning from background state.
        $actions['$foreground'] = array("type"=>"\$reload");

        /*
        // Actions on view loading for the first time.
        $actions['$load'] = array();

        // Actions on view whenever view appears.
        $actions['$show'] = array();

        // Actions on pull down.
        $actions['$pull'] = array();
        */

      $head['actions'] = $actions;


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

      $sections = array(
        "{{#each names}}"=>array(array("type"=>"horizontal","items"=>array("{{#each items}}"=>array("type"=>"vertical","style"=>array("background"=>"#fafafa","padding"=>"9","width"=>"90","height"=>"120"),"components"=>array(
          array("type"=>"label","text"=>"{{name}}","style"=>array("background"=>"#cecece","size"=>"12")))))))
      );

      $footer = array(
        "tabs"=>array(
          "style"=>array(
            "background"=>"rgba(255,255,255,0.8)",
            "color"=>"#000000"),
            "items"=>array(
              array(
                "image"=>"https://collectmemes.com/menu.png",
                "style"=>array(
                  "height"=>"21"),
                "url"=>"https://collectmemes.com/views/friends.php"),
              array("image"=>"https://collectmemes.com/menu.png",
                "style"=>array(
                  "height"=>"21"),
                "url"=>"https://collectmemes.com/views/collect.php"),
              array("image"=>"https://collectmemes.com/menu.png",
                "style"=>array(
                  "height"=>"21"),
                "url"=>"https://collectmemes.com/views/vault.php"))));

      $templateBody = array("header"=>$header,"sections"=>$sections,"footer"=>$footer);

      $templates['body'] = $templateBody;

      $head['templates'] = $templates;

    $app['head'] = $head;

  $jason['$jason'] = $app;

echo json_encode($jason, JSON_UNESCAPED_SLASHES);

?>
