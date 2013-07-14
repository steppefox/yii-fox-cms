<?php
/**
 * ENestedSortable class file.
 *
 * @author Michael de Hart <info@cloudengineering.nl>
 * @link http://www.cloudengineering.nl/
 * @copyright Copyright &copy; 2010-2011 Cloud Engineering
 * @license http://www.yiiframework.com/license/
 */

/**
 * ENestedSortable extends CWidget and implements a base class for a sortable nested tree.
 * more about nestedSortable can be found at http://mjsarfatti.com/sandbox/nestedSortable/.
 * @version: 1.0
 */
class ENestedSortable extends CWidget
{
	/**
	 * @var array the data that can be used to generate the tree view content.
	 * Each array element corresponds to a tree view node with the following structure:
	 * <ul>
	 * <li>text: string, required, the HTML text associated with this node.</li>
	 * <li>expanded: boolean, optional, whether the tree view node is in expanded.</li>
	 * <li>id: string, optional, the ID identifying the node. This is used
	 *   in dynamic loading of tree view (see {@link url}).</li>
	 * <li>hasChildren: boolean, optional, defaults to false, whether clicking on this
	 *   node should trigger dynamic loading of more tree view nodes from server.
	 *   The {@link url} property must be set in order to make this effective.</li>
	 * <li>children: array, optional, child nodes of this node.</li>
	 * </ul>
	 * Note, anything enclosed between the beginWidget and endWidget calls will
	 * also be treated as tree view content, which appends to the content generated
	 * from this data.
	 */
	public $data;

        /*
         * is array of 3 values (update, delete)
         */
        public $events;
        /**
         * @var string The javascript function to be raised when this item is clicked (client event).
         */
        public $onclick;
        public $onupdate;
        public $ondelete;
        
        public $updateUrl;
        public $deleteUrl;

        /**
         * if true the tree item can be dragged around
         */
        public $sortable = true;

	/**
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;

        /**
         * The HTML element for the list
         */
        public $listType = 'ol';
        /**
         * The class name of the items that will not accept nested lists. Default: 'ui-nestedSortable-no-nesting'
         */
        public $disableNesting = 'no-nest';
        public $errorClass = 'ui-nestedSortable-error';
        /**
         * The handle inside the items for draggin arround
         */
        public $handle = 'div';
        public $helper = 'clone';
        /**
         * The tree items
         */
        public $items = 'li';
        /**
         * How many nesting levels can the list have at the most. If set to '0' the levels are unlimited.
         */
        public $maxLevels = 0;

        public $placeholder = 'placeholder';

        public $forcePlaceholderSize = true;
        /**
         * If set to true, the item will be reverted to its new DOM position with a smooth animation.
         * Optionally, it can also be set to a number that controls the duration of the animation in ms.
         */
        public $revert;

        /**
         * How far right or left (in pixels) the item has to travel in order to be nested or to be sent outside its current list.
         */
        public $tabSize = 20;
        
	/**
	 * @var array additional options that can be passed to the constructor of the nestedSortable js object.
	 */
	public $options=array();
	/**
	 * @var array additional HTML attributes that will be rendered in the OL tag.
	 * The default tree view CSS has defined the following CSS classes which can be enabled
	 */
	public $htmlOptions;

        
        public $formatData = true;
        /**
         * This is the way the reordering behaves during drag. Possible values: 'intersect', 'pointer'. In some setups, 'pointer' is more natural.
         */
        public $tolerance  = "pointer";
        public $toleranceElement = '> div';

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the tree view content.
	 */
	public function init()
	{
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$id=$this->htmlOptions['id']=$this->getId();

		$cs=Yii::app()->getClientScript();
                
		$cs->registerCoreScript('jquery');
                
            $assets = dirname(__FILE__).'/assets';
            $baseUrl = Yii::app()->assetManager->publish($assets);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.ui.nestedSortable.js', CClientScript::POS_HEAD);    
            
            $options=$this->getClientOptions();
            $options=$options===array()?'{}' : CJavaScript::encode($options);

            if(isset($this->options['update']))
                $cs->registerScript('Yii.CNestedSortable#'.$id,"jQuery(\"#{$id}\").nestedSortable($options);");

            //Onclick for the Tree Buttons
            /* $cs->registerScript('Yii.CNestedSortableUpdate#'.$id,"jQuery(\"#{$id} a.update\").click(function(event) {
                var cat_id = $(this).parents('li').attr('id').substr(5);
                $('#categoryDialog').load(
                        '" . Yii::app()->controller->createUrl('category/update') . "?id='+ cat_id,
                        function() { $('#categoryDialog').dialog('open'); }
                );
                return false;
   			})");
            $cs->registerScript('Yii.CNestedSortableDelete#'.$id,"jQuery(\"#{$id} a.delete\").click(function(event) {
                var cat_id = $(this).parents('li').attr('id').substr(5);
                 $.ajax({
                            type: 'POST',
                            url: '".Yii::app()->controller->createUrl('category/delete')."?id='+ cat_id ,
                            dataType: 'html',
                            success: function(data){ },
                            error: function(XMLHttpRequest, textStatus, errorThrown){ alert(XMLHttpRequest.responseText); }
                        });
                 return false;
                        })"); */

            $this->handleEvents($id);


            if($this->cssFile===null)
		$cs->registerCssFile($baseUrl.'/nestedSortable.css');
            else if($this->cssFile!==false)
		$cs->registerCssFile($this->cssFile);

            echo CHtml::tag('ol',$this->htmlOptions,false,false)."\n";

            if($this->formatData)
                $data = ENestedSortable::formatDataForTree($this->data);
            else
                $data = $this->data;
            echo $this->saveDataAsHtml($data);

            

	}

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
		echo "</ol>";
	}

        protected function handleEvents($id)
        {
            $cs=Yii::app()->getClientScript();
            
            if (isset($this->onclick))
            {
                if(strpos($this->onclick,'js:')!==0)
                    $this->onclick='js:'.$this->onclick;
                $click = CJavaScript::encode($this->onclick);
                $cs->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id} li').click($click);");
            }

            if (isset($this->onupdate))
            {
                if(strpos($this->onupdate,'js:')!==0)
                    $this->onupdate='js:'.$this->onupdate;
                $update = CJavaScript::encode($this->onupdate);

                $cs->registerScript(__CLASS__.'#update'.$id,"$('#{$id} .tree-buttons').append('<a href=\"". $this->updateUrl ."\" class=\"update\">Updaten</a> ');
                    jQuery('#{$id} a.update').click($update)");
            }

            if (isset($this->ondelete))
            {
                if(strpos($this->ondelete,'js:')!==0)
                    $this->ondelete='js:'.$this->ondelete;
                $delete = CJavaScript::encode($this->ondelete);
                $cs->registerScript(__CLASS__.'#delete'.$id,"$('#{$id} .tree-buttons').append('<a href=\"#\" class=\"delete button ui-button\"><span class=\"ui-icon ui-icon-trash\"></span></a>');
                    jQuery('#{$id} a.delete').click($delete)");
            }
        }

	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		$options=$this->options;
		foreach(array('disableNesting',
                        'errorClass',
                        'handle',
                        'helper',
                        'items',
                        'maxLevels',
                        'placeholder',
                        'forcePlaceholderSize',
                        'revert',
                        'tabSize',
                        'tolerance',
                        'toleranceElement',
                    ) as $name)
		{
			if($this->$name!==null)
				$options[$name]=$this->$name;
		}
		return $options;
	}

	/**
	 * Generates tree view nodes in HTML from the data array.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the generated HTML for the tree view
	 */
	public function saveDataAsHtml($data)
	{
		$html='';
		if(is_array($data))
		{
			foreach($data as $node)
			{
				if(!isset($node['text']))
					continue;
				$id=isset($node['id']) ? (' id="list_'.$node['id'].'"') : '';
                                $css='';
				if(isset($node['hasChildren']) && $node['hasChildren'])
				{
					if($css!=='')
						$css.=' ';
					$css.='hasChildren';
				}
				if($css!=='')
					$css=' class="'.$css.'"';
				$html.="<li{$id}{$css}><div><ins class='tree-icon'>&nbsp;</ins>{$node['text']}";
                                if(isset($node['count']))
                                    $html.="<span class=\"li-count\">{$node['count']}</span>";
                                $html.="<span class=\"tree-button tree-arrow\"></span>";
                                //$html.="<span class=\"tree-button tree-cross\"></span>";
                                //$html.="<span class=\"tree-button tree-arrow\"></span>";
                                $html.='<div class="tree-buttons">';
                                if(isset($this->updateUrl))
                                        $html.="<a href=\"". $this->updateUrl ."/id/".$node['id']."\" class=\"update button ui-button\"><span class=\"ui-icon ui-icon-pencil\"></span></a> ";
                                if(isset($this->deleteUrl))
                                        $html.="<a href=\"". $this->deleteUrl ."/id/".$node['id'] ."\" class=\"delete button ui-button\"><span class=\"ui-icon ui-icon-trash\"></span></a>";
                                
                                $html.='</div>';
                                $html.="</div>";
				if(isset($node['hasChildren']) && $node['hasChildren'])
				{
					$html.="\n<ol>\n";
					$html.=$this->saveDataAsHtml($node['children']);
					$html.="</ol>\n";
				}
				$html.="</li>\n";
			}
		}
		return $html;
	}

        private function formatDataForTree($data)
        {
            $result = array();

            foreach ($data as $item)
            {
                $resultitem = array();

                $resultitem["text"] = $item->name;
                if(isset($item->itemCount))
                    $resultitem["count"] = $item->itemCount;
                $resultitem["id"] = $item->id;

                if (!empty($item->children))
                {
                    $resultitem["hasChildren"] = true;
                    $resultitem["children"] = $this->formatDataForTree($item->children);
                }

                $result[] = $resultitem;
            }

            return $result;
        }

	/**
	 * Saves tree view data in JSON format.
	 * This method is typically used in dynamic tree view loading
	 * when the server code needs to send to the client the dynamic
	 * tree view data.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the JSON representation of the data
	 */
	public static function saveDataAsJson($data)
	{
		if(empty($data))
			return '[]';
		else
			return CJavaScript::jsonEncode($data);
	}
}
