<?php
header('Content-type:application/json;charset=utf-8');

$phrases = array("Did you mean to click on Fortnite?", "Yee haw.", "Keanu Reeves uses CollectMemes.",
"You really just gonna load on by without saying Howdy?", "The aglet is the plastic tip of the shoelace.",
"Bruh moment.", "Peace was never an option.", "Faded AF is Faded AF backwards.", "What if we kissed while the app loaded? JK! Unless...",
"What even is a meme?", "Don't forget to subscribe!", "I think I've seen this one before!", "RIP Harambe.", "Mr. Stark... You know the rest.");

$phrase = $phrases[array_rand($phrases)];

$json = '{
  "$jason": {
    "head": {
      "data": {
        "load-phrases": [
          { "phrase": "?" }
        ]
      },
      "title": "Empty",
      "actions": {
        "$foreground": {
          "type": "$reload"
        }
        "$show": {
          "type": "$timer.start",
          "options": {
            "interval": "4",
            "name": "timer1",
            "repeats": "false",
            "action": {
              "type": "$render"
            }
        }
      },
      "templates": {
        "body": {
          "layers": [
            {
              "type": "image",
              "url": "https://collectmemes.com/app-images-new/gifs/logo-spin.gif",
              "style": {
                "width": "300",
                "height": "100",
                "top": "50%-100",
                "left": "50%-150"
              }
            },
            {
              "type": "label",
              "text": "What if we kissed while the app loaded? JK! Unless...",
              "style": {
                "width": "80%",
                "height": "100",
                "top": "50%-20",
                "left": "10%",
                "font": "Arial",
                "size": "16",
                "color": "#111111",
                "align": "center"
              }
            }
          ]
        }
      }
    }
  }
}';

echo $json;

?>
