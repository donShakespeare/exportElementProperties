<?php
/*
https://github.com/donShakespeare/
http://www.donshakespeare.com
(c) 2016 by donShakespeare: MODX & Content Editor Specialist
Deo Gratias!

************
https://github.com/donShakespeare/exportElementProperties

Reasons for this plugin
  1. Native MODX procedure fails to export any properties larger than a hobbit mitten
      Request-URI Too Long
      The requested URL's length exceeds the capacity limit for this server = FAIL FAIL
  2. xButtons, which is beautiful, tries to solve this issue. but it throws tantrums for me every now and now = FAIL FAIL

************
Install. Done!

Disable need for &p param in plugin's properties
Now add url parameter &p to any element Manager Page URL you are on.

  example.com/manager/?a=element/plugin/update&id=21&p
  example.com/manager/?a=element/template/update&id=1&p
  example.com/manager/?a=element/snippet/update&id=118&p
  example.com/manager/?a=element/chunk/update&id=12&p
  example.com/manager/?a=element/tv/update&id=22&p

For PropertySets (go to pset page first, and add value {name of pset} to &p)
  example.com/manager/?a=element/propertyset&p=myPSetName

To get desired order of JSON objects, intentionally sort your props and save
*/

//sniff url instead of event name.
//make ready to work even at OnManagerPageInit (for pset page)
//make these conditions user-dictatable
if((isset($_GET["a"], $_GET["id"]) && $modx->getOption("disable-p-param", $scriptProperties, false))  || (isset($_GET["a"], $_GET["id"], $_GET["p"])) || (isset($_GET["a"], $_GET["p"]) && $_GET["a"] == "element/propertyset" && $_GET["p"] !== "")){
  $themElements = array("plugin", "template", "snippet", "chunk", "tv", "propertyset");
  $thisElement = explode("/", $_GET["a"]);
  if(isset($_GET["id"])){
    $elementId = $_GET["id"];
  }
  else{
    $elementId = array("name" => $_GET["p"]);
  }
  $thisElement = $thisElement[1];
  if(in_array($thisElement, $themElements)){
    switch ($thisElement) {
      case "plugin":
        $class = "modPlugin";
        $name = "name";
        break;
      case "template":
        $class = "modTemplate";
        $name = "templatename";
        break;
      case "snippet":
        $class = "modSnippet";
        $name = "name";
        break;
      case "chunk":
        $class = "modChunk";
        $name = "name";
        break;
      case "tv":
        $class = "modTemplateVar";
        $name = "name";
        break;
      case "propertyset":
        $class = "modPropertySet";
        $name = "name";
        break;
    }

    if($obj = $modx->getObject($class, $elementId)){ //for sake of psets
      $name = $obj->get($name);
      $maProps = '[]';
      if($props = $obj->get('properties')){
        foreach ($props as $key => $value) {
          if(isset($props[$key]['desc_trans'], $props[$key]['area_trans'])){
            unset($props[$key]['desc_trans'], $props[$key]['area_trans']);
          }
          // unset($props[$key]['desc_trans'], $props[$key]['lexicon'], $props[$key]['area_trans']);
          // if(empty($props[$key]['options'])){
          //   unset($props[$key]['options']);
          // }
          // else{
          //   unset($props[$key]['options']['value']);
          //   unset($props[$key]['options']['name']);
          // }
        }
      // $maProps = json_encode($props, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT);
      $maProps = json_encode($props);
      $maProps = str_replace(array('type'), array('xtype'),$maProps);
      }
      if($thisElement == "propertyset"){
        $fire = '
        var psetTaskCounter = 0;
        var psetCheck = setInterval(function(){
          if(document.getElementById("modx-property-set-form").parentElement && document.getElementById("modx-grid-element-properties") && document.getElementById("modx-grid-element-properties").firstChild){
            psetTaskCounter++;
          }
          if(psetTaskCounter = 1){
            setTimeout(function(){
              clearInterval(psetCheck);
              psetProper();
            });
          }
        },1000);
        ';
      }
      else{
        $fire = '
        var tabs = Ext.getCmp("modx-'.$thisElement.'-tabs");
        var propertiesTab = tabs.find("id","modx-panel-element-properties");
        tabs.setActiveTab(propertiesTab[0]);
        psetProper();
        ';

      }
      $modx->regClientStartupHTMLBlock('
      <script>
        // download.js (http://danml.com/download.html) by dandavis
        function download(a,b,c){function r(a){var b=a.split(/[:;,]/),c=b[1],d="base64"==b[2]?atob:decodeURIComponent,e=d(b.pop()),f=e.length,g=0,h=new Uint8Array(f);for(g;g<f;++g)h[g]=e.charCodeAt(g);return new k([h],{type:c})}function s(a,b){if("download"in i)return i.href=a,i.setAttribute("download",m),i.innerHTML="downloading...",h.body.appendChild(i),setTimeout(function(){i.click(),h.body.removeChild(i),b===!0&&setTimeout(function(){d.URL.revokeObjectURL(i.href)},250)},66),!0;var c=h.createElement("iframe");h.body.appendChild(c),b||(a="data:"+a.replace(/^data:([\w\/\-\+]+)/,e)),c.src=a,setTimeout(function(){h.body.removeChild(c)},333)}var n,o,q,d=window,e="application/octet-stream",f=c||e,g=a,h=document,i=h.createElement("a"),j=function(a){return String(a)},k=d.Blob||d.MozBlob||d.WebKitBlob||j,l=d.MSBlobBuilder||d.WebKitBlobBuilder||d.BlobBuilder,m=b||"download";if("true"===String(this)&&(g=[g,f],f=g[0],g=g[1]),String(g).match(/^data\:[\w+\-]+\/[\w+\-]+[,;]/))return navigator.msSaveBlob?navigator.msSaveBlob(r(g),m):s(g);try{n=g instanceof k?g:new k([g],{type:f})}catch(a){l&&(o=new l,o.append([g]),n=o.getBlob(f))}if(navigator.msSaveBlob)return navigator.msSaveBlob(n,m);if(d.URL)s(d.URL.createObjectURL(n),!0);else{if("string"==typeof n||n.constructor===j)try{return s("data:"+f+";base64,"+d.btoa(n))}catch(a){return s("data:"+f+","+encodeURIComponent(n))}q=new FileReader,q.onload=function(a){s(this.result)},q.readAsDataURL(n)}return!0}
          
          function psetProper(){
            propsExportDataObj = '.$maProps.';
            propsExportData = JSON.stringify('.$maProps.', null, 2);
            var parentElement = document.getElementById("modx-grid-element-properties");
            var theFirstChild = parentElement.firstChild;
            var newElement = document.createElement("div");
            newElement.innerHTML = "<div style=\"margin:auto;margin-bottom:50px;width:98%;\"><button class=\"x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon\" onclick=download(document.getElementById(\'donshakespearePropertyDLD\').value,\''.$name.'_'.$thisElement.'_properties.json\',\'text/plain\')>Edit & Download '.$name.' Properties</button><textarea id=donshakespearePropertyDLD class=\"x-form-text x-form-field\" style=display:block;resize:vertical;height:100px;width:100%;>"+propsExportData+"</textarea></div>";
            parentElement.insertBefore(newElement, theFirstChild);
          }

          // I love ExtJS, and love mixing languages. Deal with it!
          Ext.onReady(function() {
            '.$fire.'
          },this,{delay:400});
      </script>
      ');
    }
  }
}