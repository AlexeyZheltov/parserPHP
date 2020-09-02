      <div id="menu">
        <h3>Основное меню</h3>
  <ul>
    <?php foreach ($SITE['menu'] as $site)
    { ?>
      <li><a <?php if ($site['link'] == $GET['page'][0] or ($GET['page'][0] == "main" and empty($site['link']))) { echo 'class="hover" '; } ?>href="<?php echo GVS_HOST . $site['link'] ?>"><?php echo $site['name'] ?></a></li>
      <?php
    } ?>
  </ul>
      </div> <!-- End menu-->
<p>&nbsp;</p>
