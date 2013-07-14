<?php
/**
 * EFlotGraphWidget class file.
 *
 * @author Michiel Betel <mbetel@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2010 mbetel
 * @license Public Domain
 * 
 * This code is a rippoff of the Yii JUI code 
 * kudo's to Sebastian Thierer <sebathi@gmail.com>
 * and Qiang Xue <qiang.xue@gmail.com>
 */

Yii::import('application.extensions.flot.EFlotWidget');

/**
 * CEFlotGraphWidget displays a flot graph.
 *
 * CEFlotGraphWidget encapsulates the {@link http://code.google.com/p/flot/ flot 
 * graphing plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('application.extensions.EFlotGraphWidget', array(
 *     'data'=> array(),
 *     'options'=> array(),
 *     'htmlOptions'=>array(
 *         'style'=>'width:300px;height:300px;'
 *     ),
 * ));
 * </pre>
 *
 */
class EFlotGraphWidget extends EFlotWidget
{
	/**
	 * @var string the name of the container element that contains the progress bar. Defaults to 'div'.
	 */
	public $tagName = 'div';

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		$id=$this->getId();
		$this->htmlOptions['id']=$id;        

		echo CHtml::openTag($this->tagName,$this->htmlOptions);
		echo CHtml::closeTag($this->tagName);

                $flotoptions=CJavaScript::encode($this->options);
                $placeholder = "$('#${id}')";
                
                if($this->dataSource != null)
                {
                    Yii::app()->getClientScript()->registerScript(__CLASS__.'#ajax'.$id,
                            "$.ajax({
                                url: '{$this->dataSource}',
                                method: 'GET',
                                dataType: 'json',
                                success: function(data) { $.plot($placeholder, data, $flotoptions); },
                                error: function(data) { {$placeholder}.append('<h1>Google failed to authenticate</h1>'); }
                            });");
                }
                else
                {
                    $flotdata=CJavaScript::encode($this->data);
                    Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"$.plot($placeholder, $flotdata, $flotoptions)");
                }


                if(isset($this->options['grid']) && $this->options['grid']['hoverable'])
                    Yii::app()->getClientScript()->registerScript(__CLASS__.'#b'.$id,
                            "function showTooltip(x, y, contents) {
                                $('<div id=\"tooltip\">' + contents + '</div>').css( {
                                    position: 'absolute',
                                    display: 'none',
                                    top: y - 10,
                                    left: x + 5,
                                    padding: '2px'
                                }).appendTo(\"body\").fadeIn(200);
                            }".
                            "var previousPoint = null;".
                            $placeholder.".bind('plothover', function (event, pos, item) {
                            if(item)
                            {
                                if (previousPoint != item.dataIndex) {
                                    previousPoint = item.dataIndex;
                                    var x = item.datapoint[0].toFixed(2),
                                    y = item.datapoint[1].toFixed(2);
                                    showTooltip(item.pageX, item.pageY,
                                        Math.round(y) + ' ' + item.series.label);
                                }
                            }
                            else {
                                $('#tooltip').remove();
                                previousPoint = null;
                            }
                        }
                        );");
	}

}