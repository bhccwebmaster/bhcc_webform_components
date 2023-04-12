<?php

namespace Drupal\bhcc_webform_components\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\WebformCompositeBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'bhcc_webform_components' element.
 *
 * @WebformElement(
 *   id = "bhcc_webform_family_person",
 *   label = @Translation("BHCC Webform Family Person Composite"),
 *   description = @Translation("Provides a BHCC composite family contact type"),
 *   category = @Translation("Composite elements"),
 *   multiline = TRUE,
 *   composite = TRUE,
 *   states_wrapper = TRUE,
 * )
 *
 * @see \Drupal\webform\Plugin\WebformElement\WebformCompositeBase
 * @see \Drupal\webform\Plugin\WebformElementBase
 * @see \Drupal\webform\Plugin\WebformElementInterface
 * @see \Drupal\webform\Annotation\WebformElement
 */
class BHCCWebformFamilyPerson extends WebformCompositeBase {

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

    // Add in the extra lines.
    $value = $this->getValue($element, $webform_submission, $options);
    $lines = [];
    $lines[] = ($value['first_name'] ? ' ' . $value['first_name'] : '') .
    ($value['last_name'] ? ' ' . $value['last_name'] : '') .
    ($value['date_of_birth'] ? ' ' . $value['date_of_birth'] : '');
    return $lines;

  }

}
