<?php Yii::import('application.extensions.flot.EFlotWidget'); ?>


<div id="main">
    <div class="toolbar">
        <div class="left">
            <h2>Visitor statistics of last 30 days</h2>
        </div>
        <div class="right">

<?php //button for selecting time range ?>
        </div>
    </div>

    <div id="content_form" class="form">


        <div class="one_column">
        <div class="section">
                <div class="section-header">Visitors and pageviews</div>
            <div class="section-content">
<?php
$this->widget('application.extensions.flot.EFlotGraphWidget',
    array(
        'dataSource'=>$this->createUrl('statVisitors'),
        'options'=>array(
            'legend'=>array(
                'position'=>'ne',
                'show'=>true,
                'margin'=>10,
                'backgroundOpacity'=> 0.5
            ),
            'xaxis'=>array('mode'=>'time', 'timeFormat'=>"%y %0m %0d" ,'tickLength'=>5),
            'selection'=>array('mode'=>'x'),
            'grid'=>array('hoverable'=>true),
        ),
        'htmlOptions'=>array(
               'style'=>'height:350px;'
        )
    )
);
?>
        </div>
    </div>
        </div>

        <div class="clear"></div>

        <div class="one_half">
        <div class="section">
                <div class="section-header">Statistics</div>
            <div class="section-content">
                <?php if(!empty($totals)): ?>
                <ul class="number-widget">
                    <li><span><?php echo $totals['pageviews']; ?></span> Pageviews this month</li>
                    <li><span><?php echo $totals['visits']; ?></span> Visitors this month</li>
                    <li><span><?php echo $totals['avgtimeonpage']; ?></span> Average time on site</li>
                    <li><span><?php echo $totals['visitbouncerate']; ?></span> Bouncepercentage</li>
                    <li><span><?php //echo $totals['percentnewvisits']; ?></span> <!--Percentage new visitors--></li>
                </ul>
                <?php endif; ?>
        </div>
    </div>
        </div>

        


        <div class="one_half">
            <div class="section">
                <div class="section-header">Top 10 visitor's source</div>
                <div class="section-content">

                    <?php
                    $this->widget('application.extensions.flot.EFlotGraphWidget',
                        array(
                            'dataSource'=>$this->createUrl('statSource'),
                            'options'=>array(
                                'legend'=>array('show'=>true),
                                'series'=>array(
                                    'pie'=>array(
                                        'show'=>true,
                                        'radius'=>1,
                                        'label'=>array(
                                            'show'=>true,
                                            'radius'=>'0.66',
                                            'threshold'=>'0.03',
                                            'formatter'=>"js:function(label, series){
                                                return '<div style=\"font-size:8pt;text-align:center;padding:2px;color:white;\">'+Math.round(series.percent)+'%</div>';
                                            }",
                                        ),
                                    ),
                                ),
                            ),
                            'htmlOptions'=>array(
                                   'style'=>'height:300px;'
                            )
                        )
                    );
                    ?>
                </div>
            </div>

        </div>

        <div class="one_half">
            <div class="section">
                <div class="section-header">Top 10 visitor's country</div>
                <div class="section-content">
                    <?php
                    $this->widget('application.extensions.flot.EFlotGraphWidget',
                        array(
                            'dataSource'=>$this->createUrl('statCountry'),
                            'options'=>array(
                                 'legend'=>array('show'=>true),
                                'series'=>array(
                                    'pie'=>array(
                                        'show'=>true,
                                        'radius'=>1,
                                        'label'=>array(
                                            'show'=>true,
                                            'radius'=>'0.66',
                                            'threshold'=>'0.03',
                                            'formatter'=>"js:function(label, series){
                                                return '<div style=\"font-size:8pt;text-align:center;padding:2px;color:white;\">'+Math.round(series.percent)+'%</div>';
                                            }",
                                        ),
                                    ),
                                ),
                            ),
                            'htmlOptions'=>array(
                                   'style'=>'height:300px;'
                            )
                        )
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="one_half">
            <div class="section">
                <div class="section-header">Top 10 Vistor's place</div>
                <div class="section-content">
                    <?php
                    $this->widget('application.extensions.flot.EFlotGraphWidget',
                        array(
                            'dataSource'=>$this->createUrl('statPlace'),
                            'options'=>array(
                                'legend'=>array('show'=>true),
                                'series'=>array(
                                    'pie'=>array(
                                        'show'=>true,
                                        'radius'=>1,
                                        'label'=>array(
                                            'show'=>true,
                                            'radius'=>'0.66',
                                            'threshold'=>'0.05',
                                            'formatter'=>"js:function(label, series){
                                                return '<div style=\"font-size:8pt;text-align:center;padding:2px;color:white;\">'+Math.round(series.percent)+'%</div>';
                                            }",
                                        ),
                                    ),
                                ),
                            ),
                            'htmlOptions'=>array(
                                   'style'=>'height:300px;'
                            )
                        )
                    );
                    ?>
                </div>
            </div>

        </div>

        <div class="one_column">
            <div class="section">
                <div class="section-header">20 Most viewed pages</div>
                <div class="section-content">
                    <table class="items">
                        <thead>
                        <tr>
                          <th>Page Title</th>
                          <th>Link</th>
                          <th>Pageviews</th>
                          <th>Visits</th>
                          <th>avg. Time on page</th>
                          <th>Bounce Rate</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pagepath as $result): ?>
                        <tr>
                          <td><?php echo $result->getPageTitle() ?></td>
                          <td><a href="<?php echo Yii::app()->request->hostInfo.Yii::app()->request->baseUrl.$result->getPagepath() ?>"><?php echo $result->getPagepath(); ?></a></td>
                          <td><?php echo $result->getPageviews() ?></td>
                          <td><?php echo $result->getVisits() ?></td>
                          <td><?php echo gmdate("i:s", round($result->getAvgTimeOnPage())) ?></td>
                          <td><?php echo round($result->getVisitBounceRate())."%" ?></td>
                        </tr>
                        <?php endforeach ?>
                        </tbody>
                     </table>
                </div>
            </div>
        </div>


<div class="clearboth"></div>
<br /><br /><br />

    </div>

</div>