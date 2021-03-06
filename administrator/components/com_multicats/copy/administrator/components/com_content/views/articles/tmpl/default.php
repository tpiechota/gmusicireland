<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= $listOrder == 'a.ordering';
// Multicats
$saveMOrder	= $listOrder == 'm_ordering';
$level	= $this->state->get('filter.level') == 1 ? true : false; //only with filtered 1 level we can do ordering for multicats
// recalculate saveMorder
if($saveMOrder && $this->state->get('filter.category_id') && is_numeric($this->state->get('filter.category_id')) && $level){
  $saveMOrder = true;
} else {
  $saveMOrder = false;
}
if ( $this->state->get('filter.category_id') AND is_numeric($this->state->get('filter.category_id')) ) {
  $hasCategoryFilter = true;
} else {
  $hasCategoryFilter = false;
}

/* //bypassed by multicats
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_content&task=articles.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
*/

$assoc		= JLanguageAssociations::isEnabled();


// Multicats - deprecated???
//set the pagination for Ordering POST
$app = JFactory::getApplication();

$limitstart = $app->getUserStateFromRequest('com_content.articles.limitstart','limitstart');
$limit = $app->getUserStateFromRequest('global.list.limit','limit');

//if(!$limitstart){$limitstart = 0;}
//if(!$limit){$limit = 20;}

$post['limitstart'] = (isset($_POST['limitstart'])) ? $_POST['limitstart'] : 0;
$post['limit'] = (isset($_POST['limit'])) ? $_POST['limit'] : $limit;
?>
<style>
.icon-save:before {
  content: "\47";
}
</style>
<?php

JFactory::getDocument()->addScriptDeclaration('

  /* Multicats ordering */
  function mcatsOrder(id,catid,action){
    var $cjq = jQuery.noConflict();
      $cjq(document).ready(function($) {
        $cjq().ready(function() {
          
          var func = \'setorder\';
          $cjq.ajax({
            type: "GET",                                                                             
            url:"index.php",
            data: "option=com_multicats&task=ajaxCall&function="+func+"&id="+id+"&catid="+catid+"&action="+action,
            success:function(results){
              //alert(results);
              location.reload();                         
            } // end success
          }); //end ajax
        });
      });
    
  }
  function mcatsSaveOrder(catid,dir){
    var $cjq = jQuery.noConflict();
    $cjq(document).ready(function($) {
      // Ordering
      morder = [];
      var i = 0;
      $cjq(\'input[name^="morder"]\').each(function() {
        morder[i] = $(this).val();
        i++;          
      });
      // ID
      mid = [];
      var i = 0;
      $cjq(\'input[name^="cid"]\').each(function() {
        mid[i] = $(this).val();
        i++;          
      });

      var func = \'saveorder\';
      $cjq.ajax({
        type: "POST",                                                                             
        url:"index.php?option=com_multicats&task=ajaxCall&function="+func+"&catid="+catid+"&dir="+dir+"&limitstart='.(int)$post['limitstart'].'"+"&limit='.(int)$post['limit'].'",
        data: {morder:morder,mid:mid,limitstart:'.(int)$limitstart.',limit:'.(int)$limit.'},
        success:function(results){
          //alert(results);
          location.reload();                         
        } // end success
      }); //end ajax
      
    }); //end document ready    
  }      
');

?>

<form action="<?php echo JRoute::_('index.php?option=com_content&view=articles'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="articleList">
				<thead>
					<tr>
					<?php 
           // Multicats
          if(1 == 2):?>	
            <th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
          <?php endif; // End Multicats ?>
            
          <!-- multicats order -->
    				<th width="<?php $hasCategoryFilter == true ? $width = '10%' : $width = ''; echo $width; ?>">
							<?php echo JHtml::_('searchtools.sort', '', 'm_ordering', $listDirn, $listOrder, null, 'asc', 'Ordering - for activation:<br/>- Select a category<br/>- Select max level has to be set on 1', 'icon-menu-2'); ?>            
          <?php if ( $hasCategoryFilter ) : ?>

    					<?php if ($saveMOrder) :?>
    						<a class="saveorder" href="javascript:void(0);" onclick="mcatsSaveOrder(<?php echo (int)$this->state->get('filter.category_id');?>,'<?php echo $listDirn;?>');" title="Save Order">
                  <i class="icon-save"></i>
                </a>           
                <?php //echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'articles.saveorder'); ?>
          <?php endif; ?>

    					<?php endif; ?>
    				</th>			
          <!-- multicats order -->
						<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
					<?php if ($assoc) : ?>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_CONTENT_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
						</th>
					<?php endif;?>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$item->max_ordering = 0;
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create',     'com_content.category.' . $item->catid);
					$canEdit    = $user->authorise('core.edit',       'com_content.article.' . $item->id);
					$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own',   'com_content.article.' . $item->id) && $item->created_by == $userId;
					$canChange  = $user->authorise('core.edit.state', 'com_content.article.' . $item->id) && $canCheckin;
          $cat = explode(',',$item->catid); // Multicats
          ?>
					<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo  $cat[0]; //$item->catid; // Multicats ?>">
          <?php if(1 == 2){?>
          	<td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';
							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
								<i class="icon-menu"></i>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
							<?php endif; ?>
						</td>
          <?php }?>      
          <!-- multicats order -->
            <td class="order">
          <?php if ( $hasCategoryFilter ) : ?>

    					<?php if ($canChange) : ?>
    						<?php if ($saveMOrder) : ?>
    							<?php if ($listDirn == 'ASC') : ?>
    								<span><a class="jgrid" href="javascript:void(0);" onclick="mcatsOrder('<?php echo $item->id;?>','<?php echo $this->state->get('filter.category_id');?>','orderup');"><i class="icon-arrow-up-3"></i></span>
    								<span><a class="jgrid" href="javascript:void(0);" onclick="mcatsOrder('<?php echo $item->id;?>','<?php echo $this->state->get('filter.category_id');?>','orderdown');"><i class="icon-arrow-down-3"></i></a></span>
    							<?php elseif ($listDirn == 'DESC') : ?>
    								<span><a class="jgrid" href="javascript:void(0);" onclick="mcatsOrder('<?php echo $item->id;?>','<?php echo $this->state->get('filter.category_id');?>','orderdown');"><i class="icon-arrow-down-3"></i></a></span>
    								<span><a class="jgrid" href="javascript:void(0);" onclick="mcatsOrder('<?php echo $item->id;?>','<?php echo $this->state->get('filter.category_id');?>','orderup');"><i class="icon-arrow-up-3"></i></a></span>
    							<?php endif; ?>
    						<?php endif; ?>
    						<?php $readonly = $saveMOrder ?  '' : 'readonly="readonly"'; ?>
    						<input type="text" style="width: 30px;" name="morder[]" size="5" value="<?php echo $item->m_ordering;?>" <?php echo $readonly ?> class="text-area-order" />
    					<?php else : ?>
    						<?php echo $item->m_ordering; ?>
    					<?php endif; ?>
          <?php endif; ?>        

    				</td>
          <!-- multicats order -->
                  
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="center">
							<div class="btn-group">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
								<?php echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange); ?>
								<?php
								// Create dropdown items
								$action = $archived ? 'unarchive' : 'archive';
								JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'articles');

								$action = $trashed ? 'untrash' : 'trash';
								JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'articles');

								// Render dropdown list
								echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
								?>
							</div>
						</td>
						<td class="has-context">
							<div class="pull-left break-word">
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'articles.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($item->language == '*'):?>
									<?php $language = JText::alt('JALL', 'language'); ?>
								<?php else:?>
									<?php $language = $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
								<?php endif;?>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_content&task=article.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
								<?php endif; ?>
								<span class="small break-word">
									<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
								</span>
								<div class="small">
									<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->catz); //echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
								</div>
							</div>
						</td>
						<td class="small hidden-phone">
							<?php echo $this->escape($item->access_level); ?>
						</td>
						<?php if ($assoc) : ?>
						<td class="hidden-phone">
							<?php if ($item->association) : ?>
								<?php echo JHtml::_('contentadministrator.association', $item->id); ?>
							<?php endif; ?>
						</td>
						<?php endif;?>
						<td class="small hidden-phone">
							<?php if ($item->created_by_alias) : ?>
								<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
								<?php echo $this->escape($item->author_name); ?></a>
								<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
							<?php else : ?>
								<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
								<?php echo $this->escape($item->author_name); ?></a>
							<?php endif; ?>
						</td>
						<td class="small hidden-phone">
							<?php if ($item->language == '*'):?>
								<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
						</td>
						<td class="nowrap small hidden-phone">
							<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->hits; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php echo $this->pagination->getListFooter(); ?>
		<?php // Load the batch processing form. ?>
		<?php echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
