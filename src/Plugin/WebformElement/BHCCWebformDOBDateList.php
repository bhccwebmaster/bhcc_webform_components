<?php

namespace Drupal\bhcc_webform_components\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\DateList;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'bhcc_dob_datelist' element.
 *
 * @WebformElement(
 *   id = "bhcc_dob_datelist",
 *   api = "https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Datetime!Element!Datelist.php/class/Datelist",
 *   label = @Translation("BHCC Date of Birth Date list"),
 *   description = @Translation("Provides a form element for date of birth with upper limit of today's date"),
 *   category = @Translation("BHCC"),
 * )
 */
class BHCCWebformDOBDateList extends BHCCWebformDateList{


  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties()
  {

    return [
      'date_date_max' => 'today',
    ] + parent::defineDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateConfigurationForm($form, $form_state);
    $values = $form_state->getValues();
    $values['#date_date_max'] = 'today';
    $form_state->setValues($values);
  }

}
