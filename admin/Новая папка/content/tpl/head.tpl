<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $SITE['title']?></title>
  <meta content="ru" http-equiv="Content-Language" />
  <meta http-equiv="Content-Type" content="text/html; utf-8" />
  <link href="/content/css/main/index.css" rel="stylesheet" type="text/css"/>
  <?php switch ($GET['page']){
     case 'main': ?>
    <?php case 'settings': ?>
      <link href="/content/css/main/settings.css" rel="stylesheet" type="text/css"/>
  <?php } ?>
</head> <!-- End head-->

<body>
  <div id="container">