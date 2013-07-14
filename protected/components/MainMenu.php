<?php

/**
 * MainMenu class file.
 *
 * @author Michael de Hart (info@cloudengineering.nl)
 * @copyright Copyright &copy; 2010 Cloud Engineering
 *
 */
class MainMenu extends CMenu
{
    private $baseUrl;
    private $nljs;
    
    public $cssFile;       
    public $activateParents=true;
    public $isSubMenu = false;
    
    /**
     * The javascript needed
     */
    protected function createJsCode()
    {
        $js='';
        $js .= '  $("#nav li").hover(' . $this->nljs;
        $js .= '    function () {' . $this->nljs;
        $js .= '      if ($(this).hasClass("parent")) {' . $this->nljs; 
        $js .= '        $(this).addClass("over");' . $this->nljs;
        $js .= '      }' . $this->nljs;
        $js .= '    },' . $this->nljs; 
        $js .= '    function () {' . $this->nljs;
        $js .= '      $(this).removeClass("over");' . $this->nljs;
        $js .= '    }' . $this->nljs;
        $js .= '  );' . $this->nljs;
        return $js;
    } 
      
	  
    /**
    * Give the last items css 'last' style 
    */	  
	  protected function cssLastItems($items)
	  {
      $i = max(array_keys($items));
      $item = $items[$i];

		  if(isset($item['itemOptions']['class']))
			  $items[$i]['itemOptions']['class'].=' last';
		  else
			  $items[$i]['itemOptions']['class']='last';      

			foreach($items as $i=>$item)
			{
			  if(isset($item['items']))
			  {
          $items[$i]['items']=$this->cssLastItems($item['items']);
        }
      }
      
      return array_values($items);
    }
 
     /**
    * Give the last items css 'parent' style 
    */	  
	  protected function cssParentItems($items)
	  {
	  	foreach($items as $i=>$item)
	  	{
	  		if(isset($item['items']))
	  		{
 		      if(isset($item['itemOptions']['class']))
			      $items[$i]['itemOptions']['class'].=' parent';
		      else
			      $items[$i]['itemOptions']['class']='parent'; 
	  		
	  		$items[$i]['items']=$this->cssParentItems($item['items']);
	  		}
      }
      
      return array_values($items);
    }
    
    /**
    * Initialize the widget
    */
    public function init()
    {
        if(!$this->getId(false))
          $this->setId('nav');

        $this->nljs = "\n";
        $this->items=$this->cssParentItems($this->items);
        $this->items=$this->cssLastItems($this->items);

        parent::init();
    }
    
    /**
    * Registers the external javascript files
    */
    public function registerClientScripts()
    {
        // add the script
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        
        $js = $this->createJsCode();
        $cs->registerScript('mbmenu_'.$this->getId(), $js, CClientScript::POS_READY);
    }
    
	protected function renderMenuRecursive($items)
	{
		foreach($items as $item)
	  	{
	  		echo CHtml::openTag('li', isset($item['itemOptions']) ? $item['itemOptions'] : array());
	  	  	if(isset($item['url']))
	  	  		echo CHtml::link('<span>'.$item['label'].'</span>',$item['url'],isset($item['linkOptions']) ? $item['linkOptions'] : array());
	  	  	else
	  	  		echo CHtml::link('<span>'.$item['label'].'</span>',"javascript:void(0);",isset($item['linkOptions']) ? $item['linkOptions'] : array());
	  	  	if(isset($item['items']) && count($item['items']))
	  	  	{
	  	  		echo "\n".CHtml::openTag('ul',$this->submenuHtmlOptions)."\n";
	  	  		$this->renderMenuRecursive($item['items']);
	  	  		echo CHtml::closeTag('ul')."\n";
	  	  	}
	  	  	echo CHtml::closeTag('li')."\n";
	  	}
	}

	  protected function normalizeItems($items,$route,&$active, $ischild=0)
	  {
	  	foreach($items as $i=>$item)
	  	{
	  		if(isset($item['visible']) && !$item['visible'])
	  		{
	  			unset($items[$i]);
	  			continue;
	  		}
	  		if($this->encodeLabel)
	  			$items[$i]['label']=CHtml::encode($item['label']);
	  		$hasActiveChild=false;
	  		if(isset($item['items']))
	  		{
	  			$items[$i]['items']=$this->normalizeItems($item['items'],$route,$hasActiveChild, 1);
	  			if(empty($items[$i]['items']) && $this->hideEmptyItems)
	  				unset($items[$i]['items']);
	  		}
	  		if(!isset($item['active']))
	  		{
	  			if(($this->activateParents && $hasActiveChild) || $this->isItemActive($item,$route))
	  				$active=$items[$i]['active']=true;
	  			else
	  				$items[$i]['active']=false;
	  		}
	  		else if($item['active'])
	  			$active=true;
	  		if($items[$i]['active'] && $this->activeCssClass!='' && !$ischild)
	  		{
	  			if(isset($item['itemOptions']['class']))
	  				$items[$i]['itemOptions']['class'].=' '.$this->activeCssClass;
	  			else
	  				$items[$i]['itemOptions']['class']=$this->activeCssClass;
	  		}
	  	}
	  	return array_values($items);
	  }
    
    /**
    * Run the widget
    */
    public function run()
    {
          //$this->publishAssets();
          $this->registerClientScripts();
          if($this->isSubMenu)
          	$this->renderSubMenu();
          else
          	$this->renderMenu($this->items);

    }
    
    protected function renderSubMenu()
    {
    	//find active items, render its items if any else render menu
    	$activeItem = $this->findActiveParent($this->items);
    	//check if active item has children else render the root menu
    	if(!isset($activeItem['items']) || count($activeItem['items']) == 0)
    	{
    		$activeItem['items'] = $this->items;
    	}
    	//echo $activeItem['label'];
    	echo CHtml::openTag('ul',$this->submenuHtmlOptions)."\n";
    	foreach($activeItem['items'] as $item)
    	{
    		echo CHtml::openTag('li', isset($item['itemOptions']) ? $item['itemOptions'] : array());
    		if(isset($item['url']))
	  	  		echo CHtml::link('<span>'.$item['label'].'</span>',$item['url'],isset($item['linkOptions']) ? $item['linkOptions'] : array());
	  	  	else
	  	  		echo CHtml::link('<span>'.$item['label'].'</span>',"javascript:void(0);",isset($item['linkOptions']) ? $item['linkOptions'] : array());
    		echo CHtml::closeTag('li')."\n";
    	}
    	echo CHtml::closeTag('ul')."\n";

    }
    
    /**
     * Get the active menu item and see if any of its children are active
     * @param array $items menu items
     */
    protected function findActiveParent($items)
    {
    	foreach($items as $item)
    	{
    		if($item['active'])
    		{
    			if(isset($item['items']) && count($item['items']))
    			{
    				$this->findActiveParent($item['items']);
    			}
    			return $item;
    		}
    	}
    }
	
}