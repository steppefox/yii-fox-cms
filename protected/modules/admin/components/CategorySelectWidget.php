<?php
/**
 * Category select widget
 * Select categories for content
 * 
 * @author Michael de Hart
 * @package System 4 CMS
 * @since 1.0.0
 */
class CategorySelectWidget extends CInputWidget
{
	public $width = '200px'; //Default width
	public $height = '400px'; // Default height
	
	public $selectedcategories = array(); // Categories that are already selected
	public $attribute; // The MANY_MANY relation that represents the categories

	/**
	 * Executes the widget.
	 * Renders the checklist
	 */
	public function init()
	{
		$this->findCategories();
		
		list($name, $id) = $this->resolveNameID();

      	$this->htmlOptions['id'] = $id;

        $relation = $this->model->getActiveRelation($this->attribute);
        $relationClass = (!empty($relation->className)) ? $relation->className : "Category";
        $class = new $relationClass;
      	$categories = $class->getTree();
      	
      	$this->buildHtml($categories);
	}
	
	private function findCategories()
	{
            if(!$this->model->isNewRecord)
            {
		foreach($this->model->{$this->attribute} as $category)
	      	{
	      		$this->selectedcategories[$category->id] = $category->id;
	      	}
            }
	}
	
	private function buildHtml($categories)
	{
		echo "<div style=\"background-color: #fff; padding-left: 5px; border: 1px solid grey; display: block; overflow-x: auto; overflow-y: scroll; height: $this->height; width: $this->width;\">";
		$this->buildCategories($categories);
		echo "</div>";
		//echo "<a href=\"#\">Add new category</a> ";
	}
	
	private function buildCategories($categories)
	{
		echo "<ul class=\"categorychecklist\">";
		foreach($categories as $category)
		{
			$checked = (array_key_exists($category->id, $this->selectedcategories)) ? "checked=\"checked\"" : "";
			echo "<li style=\"margin-top: 3px;\" id=\"category-$category->id\"><label><input type=\"checkbox\" $checked id=\"in-category-$category->id\" name=\"Categories[]\" value=\"$category->id\"> $category->title</label>";
			if(!empty($category->children))
			{
				$this->buildCategories($category->children);
			}
			echo "</li>";
		}
		echo "</ul>";
	}

}
?>