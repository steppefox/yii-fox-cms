<?php

/**
 * CAdjacencyBehavior class file.
 *
 * @author Michael de Hart <info@cloudengineering.nl>
 * @link http://www.cloudengineering.nl
 * @version 0.1
 */
/* The CAdjacencyBehavior extension adds up some functionality to the default
 * possibilites of yii�s ActiveRecord implementation.
 * 
 * This behavior adds function for using items like content or categories in a tree model
 * The owner of the Adjacency behavior is required to have the following:
 * 
 * - a relation the its children by parent_id or a attribute $children
 * - a relation to its parent
 * 
 *
 * public function behaviors(){
 *         return array( 'CAdjacencyBehavior' => array(
 *               'class' => 'application.components.CAdjacencyBehavior'));
 *         }                                  
 */

class AdjacencyBehavior extends CActiveRecordBehavior
{

    public $id = 'id';     // attribute name for the id value (PK)\
    public $text = 'title';   // attribute name for the text in dropdowns en trees
    public $parent = 'parent_id'; // attribute name of the parent id
    public $extrafields = array(); // attribute names for the extra field for displaying in the TreeGrid
    public $foreignkey = null;  // Build tree only with item of the with the same foreignkey attribute
    public $actions = array(
        'update' => array('icon' => 'ui-icon-pencil', 'action' => 'update'),
        'delete' => array('icon' => 'ui-icon-trash', 'action' => 'delete'),
    );         // What are ther action buttons on the TreeGrid
    private $_items;
    private $_treehref;

    /**
     * returns the content item that can be set as parent of this object
     */
    public function getValidParents($items = null)
    {
        if ($items === null)
        {
            $criteria = new CDbCriteria(array('select' => "$this->id , $this->text , $this->parent"));
            if ($this->foreignkey === null)
                $items = $this->owner->findAll($criteria);
            else
                $items = $this->owner->findByAttributes(array($this->foreignkey => $this->owner->{$this->foreignkey}), $criteria);
        }

        if (empty($items)) //no parents found
            return array();

        $treeitems = $this->arrayToTree($items);

        return $this->parentTreeToArray($treeitems);
    }
    
    public function getAllIds($root_id = null)
    {
        if($root_id == null)
            $root_id = $this->owner->id;
            
        $items = $this->getTree();
        
        $ids = array();
        foreach ($items as $item)
        {
            if ($item->id == $root_id)
                $ids = $ids + $this->idLoop(array($item));
        }
        return $ids;
    }
    private function idLoop($tree)
    {
        $array = array();
        foreach ($tree as $item)
        {
                $array[$item->id] = "$item->id";
                $children = $item->children;
                if (!empty($children))
                    $array = $array + $this->idLoop($children);
        }
        return $array;
    }

    /**
     * returns the items as a tree with the children objects in the $childern propperty
     */
    public function getTree($items = null)
    {
        if ($items === null)
        {
            if($this->owner->hasAttribute('position'))
                $items = $this->owner->findAll(array('order'=>'t.position'));
            else
                $items = $this->owner->findAll();
        }
        $tree = $this->arrayToTree($items);

        return $tree;
    }
    
    public function getDropDownTree()
    {
        $items = $this->getTree();
        
        return $this->parentTreeToArray($items, 0);
    }

    /**
     * Returns the same as findAll but sorted so sibling come after the parents
     * also calculates the level of the current item
     * @param unknown_type $items
     */
    public function getArray($tree = null)
    {
        if (empty($tree))
            $tree = $this->getTree();
        $array = array();
        foreach ($tree as $item)
        {
            $array[] = $item;
            if (!empty($item->children))
                $array = array_merge($array, $this->treeToArray($item->children, 1));
        }
        return $array;
    }

    private function treeToArray($tree, $level)
    {
        $array = array();
        foreach ($tree as $item)
        {
            $array[] = $item;
            $children = $item->children;
            //$item->level = $level;
            if (!empty($children))
            {
                $array = array_merge($array, $this->treeToArray($children, $level + 1));
            }
        }
        return $array;
    }

    /**
     * Generated a JSON string that can be used by jqGrid for displaying a TreeGrid
     * @return string $response
     */
    public function getTreeGrid($items = null)
    {
        if ($items === null)
        {
            $items = $this->owner->findAll();
            $treeItems = $this->arrayToTree($items);
        } else
        {
            $treeItems = $this->arrayToTree($items);
        }

        //Needs to be set to something if treegrids need pagination
        $response->page = 0;
        $response->total = 0;
        $response->records = 0;

        $response->rows = $this->formatTreeGrid($treeItems, 0);

        return CJavaScript::jsonEncode($response);
    }

    /**
     * format the data to be used in a jqGridTree
     * @param array $items (content categories)
     * @param integer $level
     * @return array $rows (for jqgrid)
     */
    private function formatTreeGrid($items, $level)
    {
        $rows = array();

        $id = $this->id;
        $text = $this->text;

        foreach ($items as $item)
        {
            $id = $this->$id;
            $row['id'] = $item->$id;
            $cell = array();
            $cell[] = $item->$id;
            $cell[] = $item->$text;
            //Extra field
            foreach ($this->extrafields as $field)
                $cell[] = $item->$field;
            $act = "";
            if (!empty($this->actions))
            {
                foreach ($this->actions as $key => $value)
                {
                    $act .= "<a href='" . Yii::app()->getController()->createUrl($value['action'], array('id' => $item->{$id})) . "' rel='" . $item->$id . "' title='$key' class='$key ui-state-default ui-button'><span class='ui-icon " . $value['icon'] . "'></span></a>";
                }
            }
            $cell[] = $act;
            $cell[] = $level;
            $cell[] = $item->parent_id;
            $cell[] = empty($item->children);
            $cell[] = true; //True if expanded

            $row['cell'] = $cell;
            $rows[] = $row;

            if (!empty($item->children))
                $rows = array_merge($rows, $this->formatTreeGrid($item->children, $level + 1));
        }
        return $rows;
    }

    /**
     * Generated a JSON string that can be used by jqGrid for displaying a TreeGrid
     * @param string $href the link of the node item, pk is also passed is $_GET[id]
     * @return string $response
     */
    public function getJsTree($href = null)
    {
        $this->_treehref = $href;
        $items = $this->owner->findAll();
        $treeItems = $this->arrayToTree($items);

        $response = $this->formatJSTreeData($treeItems);

        return CJavaScript::jsonEncode($response);
    }
    
    public function getJSONTree()
    {
        $treeItems = $this->getTree();

        $response = $this->formatTreeData($treeItems);

        return $response;
    }
    
    private function formatTreeData($items)
    {
        $result = array();
        
        foreach ($items as $item)
        {
                $res = array();
                $res['text'] = $item->{$this->text};
                $res['id'] = $item->id;
                $res['children'] = $this->formatTreeData($item->children);
                $res['count'] = $item->itemCount;
                $result[] = $res;
        }
        
        return $result;
    }

    /**
     * build a JSON string that can be used by jsTree
     * @param $categories
     * @return array of categories
     */
    private function formatJSTreeData($items)
    {
        $result = array();

        foreach ($items as $item)
        {
            if (!empty($item->children))
            {
                $result[] = array(
                    "data" => $item->{$this->text} . " (" . $item->itemCount . ")",
                    "attr" => array(
                        'id' => 'treenode_' . $item->id,
                        'rel' => $item->id,
                    //'href'=>Yii::app()->controller->createUrl($this->_treehref, array('cat_id'=>$item->id)),
                    ),
                    "children" => $this->formatJSTreeData($item->children),
                    "state" => "open",
                );
            } else
            {
                $result[] = array(
                    "data" => $item->{$this->text} . " (" . $item->itemCount . ")",
                    "attr" => array(
                        'id' => 'treenode_' . $item->id,
                        'rel' => $item->id,
                    //'href'=>Yii::app()->controller->createUrl($this->_treehref, array('cat_id'=>$item->id)),
                    ),
                );
            }
        }

        return $result;
    }

    /**
     * Put everyone into a tree array
     * Returns an array with all the items where parent_id = NULL
     * The attribute children of the items gets filled with his children.
     * @param $array
     */
    private function arrayToTree($array)
    {
        $this->_items = $array;

        $rootItems = array();
        foreach ($this->_items as $item)
        {
            //Only root items
            if (empty($item->parent_id))
                $rootItems[] = $this->findChildren($item);
        }
        return $rootItems;
    }

    /**
     * used by the arrayToTree function for recursive-ity
     * @param $item
     */
    private function findChildren($item)
    {
        $item->children = array();
        foreach ($this->_items as $arrayitem)
        {
            if ($arrayitem->parent_id == $item->id)
                $item->children[] = $this->findChildren($arrayitem);
        }
        return $item;
    }

    /**
     * Converts a tree into an array for displaying in a list or a dropdown menu
     * @param $tree
     * @return $array
     */
    private function parentTreeToArray($tree, $level = 0)
    {
        $array = array();
        foreach ($tree as $item)
        {
            //Excludes the owner and his children
            if ($item->id != $this->owner->id)
            {
                $dashes = "";
                for($i = 0; $i < $level; $i++)
                        $dashes .= "-";
                
                $key = "$item->id"; //TODO: report bug to PHP it wont work without the space
                $array[$key] = $dashes . " " .$item->{$this->text};
                $children = $item->children;
                if (!empty($children))
                {
                    $array = $array + $this->parentTreeToArray($children, $level+1);
                }
            }
        }
        return $array;
    }
}