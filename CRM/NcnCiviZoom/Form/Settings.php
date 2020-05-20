<?php

use CRM_NcnCiviZoom_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_NcnCiviZoom_Form_Settings extends CRM_Core_Form {
  public function buildQuickForm() {

    // add form elements
    $this->add('password', 'api_key', ts('Api Key'), array(
      'size' => 48,
    ), TRUE);
    $this->add('password', 'secret_key', ts('Secret Key'), array(
      'size' => 48,
    ), TRUE);
    $this->add('text', 'base_url', ts('Base Url'), array(
      'size' => 48,
    ), TRUE);
    $this->add('text', 'custom_field_id', ts('Custom Field Id'), array(
      'size' => 48,
    ), TRUE);
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    //Set default Values
    $defaults = CRM_NcnCiviZoom_Utils::getZoomSettings();

    $this->setDefaults($defaults);
    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    $zoomSettings['api_key']      = $values['api_key'];
    $zoomSettings['secret_key']   = $values['secret_key'];
    $zoomSettings['base_url']     = $values['base_url'];
    $zoomSettings['custom_field_id'] = $values['custom_field_id'];
    CRM_Core_BAO_Setting::setItem($zoomSettings, ZOOM_SETTINGS, 'zoom_settings');
    CRM_Core_Session::setStatus(E::ts('Your Settings have been saved'), ts('Zoom Settings'), 'success');
    $redirectUrl    = CRM_Utils_System::url('civicrm/Zoom/settings', 'reset=1',  TRUE, NULL, FALSE, TRUE);
    CRM_Utils_System::redirect($redirectUrl);
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}