<?php

namespace Drupal\bhcc_webform_components\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\WebformCompositeBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'bhcc_webform_components' element.
 *
 * @WebformElement(
 *   id = "bhcc_webform_person_requires_phone_number",
 *   label = @Translation("BHCC Webform Person Composite - Requires at least one phone number"),
 *   description = @Translation("Provides a BHCC composite person type and enforces a minimum of one phone number"),
 *   category = @Translation("Composite elements"),
 *   multiline = TRUE,
 *   composite = TRUE,
 *   states_wrapper = TRUE,
 * )
 *
 * @see \Drupal\bhcc_webform_components\Element\BHCCWebformPersonExample
 * @see \Drupal\webform\Plugin\WebformElement\WebformCompositeBase
 * @see \Drupal\webform\Plugin\WebformElementBase
 * @see \Drupal\webform\Plugin\WebformElementInterface
 * @see \Drupal\webform\Annotation\WebformElement
 */
class BHCCWebformPerson_RequiresPhoneNumber extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  protected function formatHtmlItemValue(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    return $this->formatTextItemValue($element, $webform_submission, $options);
  }

  /**
   * {@inheritdoc}
   */
  protected function formatTextItemValue(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    $value = $this->getValue($element, $webform_submission, $options);

    $lines = [];
    $lines[] = ($value['first_name'] ? $value['first_name'] : '') .
      ($value['last_name'] ? ' ' . $value['last_name'] : '') .
      ($value['mobile_phone'] ? ' ' . $value['mobile_phone'] : '') .
      ($value['landline_phone'] ? ' ' . $value['landline_phone'] : '') .
      ($value['email_address'] ? ' ' . $value['email_address'] : '') ;

    return $lines;
  }

}
