<?php
header('Content-type:application/json;charset=utf-8');

$json = '
{
  "$jason": {
    "head": {
      "data": {
        "load-phrases": [
          { "phrase": "Yee haw." }
        ]
      },
      "title": "Empty",
      "actions": {
        "$foreground": {
          "type": "$reload"
        }
      },
      "templates": {
        "body": {
          "background": "#edf8ff",
          "header": {
            "style": {
              "background": "#ffffff"
            },
            "title": {
              "type": "image",
              "style": {
                "width": "94",
                "height": "27"
              },
              "url": "https://collectmemes.com/img/logo.png"
            },
            "menu": {
              "image": "https://collectmemes.com/img/menu.png"
            }
          },
          "sections": [
            {
              "items": [
                {
                  "type": "label",
                  "text": "Collect A Meme",
                  "style": {
                    "color": "#2f3542",
                    "align": "center",
                    "size": "20",
                    "height":"60"
                  }
                },
                {
                  "type": "html",
                  "text": "<span style=\"text-align:center;\"><span style=\"color:green;\">x</span> Spins Left</span>",
                  "style": {
                    "height":"60"
                  }
                }
              ]
            },
            {
            "type": "vertical",
            "items": [
              {
                "type": "horizontal",
                "style": {
                  "align": "center"
                },
                "components": [
                  {
                    "type": "space",
                    "style": {
                      "height": "10",
                      "width": "50%-150",
                      "background": "#00ff00"
                    }
                  },
                  {
                    "type": "button",
                    "text": "Btn1",
                    "style": {
                      "width": "120",
                      "height": "40",
                      "background": "#ffffff",
                      "border_width": "1",
                      "border_color": "#000000",
                      "color": "#000000",
                      "font": "HelveticaNeue",
                      "size": "20"
                    },
                    "href": {
                      "url": "https://www.jasonclient.org/next.json",
                      "view": "jason"
                    }
                  },
                  {
                    "type": "space",
                    "style": {
                      "height": "10",
                      "width": "40",
                      "background": "#ff0000"
                    }
                  },
                  {
                    "type": "button",
                    "text": "Btn2",
                    "style": {
                      "width": "120",
                      "height": "40",
                      "background": "#0000ff",
                      "border_width": "1",
                      "border_color": "#000000",
                      "color": "#ffffff",
                      "font": "HelveticaNeue",
                      "size": "20"
                    }
                  },
                  {
                    "type": "space",
                    "style": {
                      "height": "10",
                      "width": "50%-150",
                      "background": "#ff0000"
                    }
                  }
                ]
              }
            ]
          }],
          "footer": {
            "tabs": {
              "style": {
                "background": "rgba(255,255,255,1)",
                "color": "#000000"
              },
              "items": [
                {
                  "image": "https://collectmemes.com/img-new/friends.png",
                  "style": {
                    "height": "21"
                  },
                  "url": "https://collectmemes.com/views/friends.php"
                },
                {
                  "image": "https://collectmemes.com/img-new/collect.png",
                  "style": {
                    "height": "21"
                  },
                  "url": "https://collectmemes.com/views/collect.php"
                },
                {
                  "image": "https://collectmemes.com/img-new/vault.png",
                  "style": {
                    "height": "21"
                  },
                  "url": "https://collectmemes.com/views/vault.php"
                }
              ]
            }
          }
        }
      }
    }
  }
}
';

echo $json;

?>
