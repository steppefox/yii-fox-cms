<?php
Yii::import('zii.widgets.grid.CGridView');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GridView
 *
 * @author Michael
 */
class GridView extends CGridView
{
    public $cssFile = false;
    /**
     * Renders the data items for the grid view.
     */
    public function renderItems()
    {
        if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
        {
            echo "<table class=\"header\">\n";
            
            $this->renderTableHeader();
            
            echo "</table>";
            
            echo "<div class=\"grid-body\">";
            echo "<table class=\"{$this->itemsCssClass}\">\n";
            ob_start();
            $this->renderTableBody();
            $body=ob_get_clean();
            $this->renderTableFooter();
            echo $body; // TFOOT must appear before TBODY according to the standard.
            echo "</table>";
            echo "</div>";
        }
        else
            $this->renderEmptyText();
    }

}
?>
