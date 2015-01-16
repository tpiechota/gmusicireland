<?php
/**
 * @package         Advanced Module Manager
 * @version         4.18.3
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('predefinedlist');

/**
 * Selection field for module types
 */
class JFormFieldModule extends JFormFieldPredefinedList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Module';

	protected function getOptions()
	{
		$model = new AdvancedModulesModelModules;
		$client_id = $model->getState('stfilter.client_id');

		return array_merge(JFormFieldList::getOptions(), ModulesHelper::getModules($client_id));
	}
}
