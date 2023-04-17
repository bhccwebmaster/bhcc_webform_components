<?php

namespace Drupal\bhcc_webform_components\Element;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Element\WebformCompositeBase;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Render\Element;
use Drupal\bhcc_webform\BHCCWebformHelper;

/**
 * Provides a 'bhcc_webform_components'.
 *
 * Webform composites contain a group of sub-elements for a person.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. webform_address)
 *
 * @FormElement("bhcc_webform_family_person")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 */
class BHCCWebformFamilyPerson extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {

    // Generate a unique ID that can be used by #states.
    $html_id = Html::getUniqueId('bhcc_webform_family_person');

    $elements['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#required_error' => 'Please provide a first name.',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--first_name',
        // TO DO - check styling requirements.
        'class' => ['bhcc-webform-person--first_name'],
      ],
    ];

    $elements['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last name'),
      '#required_error' => 'Please provide a last name.',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--last_name',
        // TO DO - check styling requirements.
        'class' => ['bhcc-webform-person--last_name'],
      ],
    ];

    $elements['date_of_birth'] = [
      '#type' => 'datelist',
      '#title' => t('Date of birth'),
      '#after_build' => [[get_called_class(), 'afterBuildDate']],
      '#attributes' => [
        'Data-webform-composite-id' => $html_id . '--date_of_birth',
        'class' => ['bhcc-webform-person--date_of_birth'],
      ],
      '#required_error' => 'Please provide either date of birth or age.',
      '#date_date_min' => '01/01/1900',
      '#date_date_max' => 'today',
      '#date_part_order' => ['day', 'month', 'year'],
      '#date_text_parts' => ['day', 'month', 'year'],
      '#description' => t('For example 08/02/1982'),
      // '#element_validate' => [[get_called_class(), 'validateDatelist']],
    ];

    $elements['family_relationship_to_you'] = [
      '#type' => 'select',
      '#title' => t('Relationship to you'),
      '#options' => 'bhcc_family_relationship_to_you',
      '#required_error' => 'Please tell us what their relationship is to you.',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--family_relationship_to_you',
        'class' => ['bhcc-webform-person--family_relationship_to_you'],
      ],
    ];
    return $elements;
  }

  /**
   * Performs the after_build callback.
   */
  public static function afterBuildDate(array $element, FormStateInterface $form_state) {
    // Set the property of the date of birth field.
    $element['day']['#attributes']['placeholder'] = t('DD');
    $element['day']['#maxlength'] = 2;
    $element['day']['#attributes']['class'][] = 'person-date--day';

    $element['month']['#attributes']['placeholder'] = t('MM');
    $element['month']['#maxlength'] = 2;
    $element['month']['#attributes']['class'][] = 'person-date--month';

    $element['year']['#attributes']['placeholder'] = t('YYYY');
    $element['year']['#maxlength'] = 4;
    $element['year']['#attributes']['class'][] = 'person-date--year';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function validateWebformComposite(&$element, FormStateInterface $form_state, &$complete_form) {

    // @todo do we need validateDatelist (above) now with the fix below ????? 22/10/2021
    // dpm($element);
    $value = NestedArray::getValue($form_state->getValues(), $element['#parents']);
    $element_key = end($element['#parents']);

    // If not visible - don't validate.
    if (!Element::isVisibleElement($element)) {
      return;
    }

    // If the element or any of its parent containers are hidden by conditions,
    // Bypass validation and clear any required element errors generated
    // for this element.
    if (!BHCCWebformHelper::isElementVisibleThroughParent($element, $form_state, $complete_form)) {
      // \Drupal::messenger()->addStatus(t('its NOT visible"'), 'status');
      $form_errors = $form_state->getErrors();
      $form_state->clearErrors();
      foreach ($form_errors as $error_key => $error_value) {
        if (strpos($error_key, $element_key . ']') !== 0) {
          $form_state->setErrorByName($error_key, $error_value);
        }
      }
      return;
    }

    // Otherwise deal with any validation thats needed
    // 1) first_name is needed
    // 2) last_name is needed
    // 3) date_of_birth must be selected
    // 4) family_relationship_to_you  must be selected.
    // 1) first_name is needed.
    if (empty($value['first_name'])) {
      $form_state->setErrorByName('first_name', "Please provide a first name");
    }

    // 2) last_name is needed
    if (empty($value['last_name'])) {
      $form_state->setErrorByName('last_name', "Please provide a last name");
    }

    // 3) date_of_birth must be selected
    if (empty($value['date_of_birth'])) {
      $form_state->setErrorByName('date_of_birth', "Please provide a date of birth.");
    }

    // 4) family_relationship_to_you  must be selected
    if (empty($value['family_relationship_to_you'])) {
      $form_state->setErrorByName('family_relationship_to_you', "Please tell us what their relationship is to you.");
    }
  }

}
