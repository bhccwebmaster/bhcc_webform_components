<?php

namespace Drupal\bhcc_webform_components\Element;

use Drupal\Core\Datetime\Element\Datelist;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a datelist element.
 *
 * @FormElement("bhcc_datelist")
 */
class BHCCWebformDateList extends Datelist{


  /**
   * {@inheritdoc}
   */
  public function getInfo()
  {



    $parentInfo = parent::getInfo();
    $childInfo = [
      '#date_part_order' => ['day', 'month', 'year'],
      '#date_text_parts' => ['day', 'month', 'year'],
    ];
    $returnInfo = array_replace($parentInfo, $childInfo);
    return $returnInfo;
  }

}
