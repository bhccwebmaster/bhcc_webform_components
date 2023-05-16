<?php

namespace Drupal\bhcc_webform_components\Element;

/**
 * Provides a datelist element.
 *
 * @FormElement("bhcc_dob_datelist")
 */
class BHCCWebformDOBDateList extends BHCCWebformDateList {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $parentInfo = parent::getInfo();
    $childInfo = [
      '#description' => $this->t('For example 08/02/1982'),
    ];
    $returnInfo = array_replace($parentInfo, $childInfo);
    return $returnInfo;
  }

}
