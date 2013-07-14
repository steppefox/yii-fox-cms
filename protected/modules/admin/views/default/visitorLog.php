<?php Yii::import('application.extensions.flot.EFlotWidget'); ?>


<div id="main">
    <div class="toolbar">
        <div class="left">
            <h2>Last visitors</h2>
        </div>
        <div class="right">

<?php //button for selecting time range ?>
        </div>
    </div>

    <div id="content_form" class="form">

    <table class="items">
        <thead>
        <tr>
          <th>Date</th>
          <th>Visitor</th>
          <th>Refered URL</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($stats->getLog() as $result): ?>
        <tr>
          <td><?php echo $result->getDate(); ?></td>
          <td>
              <?php echo $result->getBrowser() . " " . $result->getCountry(); ?><Br/>
              <?php echo $result->getOperatingSystem() //. " " . $result->getOperatingSystemVersion(); ?><Br/>
          </td>
            <td>
              <?php echo $result->getSource(); ?><br/>
            </td>
          <td>
            <?php echo gmdate("i:s", round($result->getVisitLength())) ?><br/>
            <?php echo $result->getPageTitle() ?><br />
          </td>
        </tr>
        <?php endforeach ?>
        </tbody>
     </table>



<div class="clearboth"></div>
<br /><br /><br />

    </div>

</div>