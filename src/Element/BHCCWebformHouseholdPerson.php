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
 * @FormElement("bhcc_webform_household_person")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 */
class BHCCWebformHouseholdPerson extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {

    // Generate a unique ID that can be used by #states.
    $html_id = Html::getUniqueId('bhcc_webform_household_person');

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

    // To do replace with yes_no predefined option.
    $elements['select_dob_or_age'] = [
      '#type' => 'radios',
      '#title' => 'Do you know their date of birth?',
      '#options' => 'yes_no',
      '#required_error' => 'Please provide either date of birth or age.',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--dob_or_age',
        'class' => ['bhcc-webform-person--dob_or_age'],
      ],
    ];

    $elements['dob_group'] = [
      '#type' => 'container',
      '#description' => t('Container for the DOB.'),
      '#after_build' => [[get_called_class(), 'afterBuildDOBContainer']],
    ];

    // $elements['dob_group']['datelist'] = [
    $elements['dob_group']['date_of_birth'] = [
      '#type' => 'datelist',
      '#title' => t('Date of birth'),
      '#after_build' => [[get_called_class(), 'afterBuildDate']],
      '#date_date_min' => '01/01/1900',
      '#date_date_max' => 'today',
      '#date_part_order' => ['day', 'month', 'year'],
      '#date_text_parts' => ['day', 'month', 'year'],
      '#description' => 'For example 08/02/1982',
      '#required_error' => 'Please provide either date of birth or age.',
      '#attributes' => [
        'id' => 'household_composite--date',
        'data-webform-composite-id' => $html_id . '--date_of_birth',
        'class' => ['bhcc-webform-person--date_of_birth'],
      ],
    ];

    $elements['age'] = [
      '#type' => 'number',
      '#title' => t('Age'),
      '#min' => 0,
      '#max' => 140,
      '#required_error' => 'Please provide either date of birth or age.',
      '#size' => 3,
      '#after_build' => [[get_called_class(), 'afterBuildAge']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--age',
        'class' => ['person-date--age'],
      ],
    ];

    return $elements;
  }

  /**
   * Function for the elements within the AfterBuild.
   */
  public static function afterBuildDate(array $element, FormStateInterface $form_state) {

    // Set the property of the date of birth elements.
    $element['day']['#attributes']['placeholder'] = t('DD');
    $element['day']['#maxlength'] = 2;
    $element['day']['#attributes']['class'][] = 'person-date--day';

    // Set the required error only on the day field.
    $element['day']['#required_error'] = 'Please give either date of birth or age.';

    $element['month']['#attributes']['placeholder'] = t('MM');
    $element['month']['#maxlength'] = 2;
    $element['month']['#attributes']['class'][] = 'person-date--month';

    $element['year']['#attributes']['placeholder'] = t('YYYY');
    $element['year']['#maxlength'] = 4;
    $element['year']['#attributes']['class'][] = 'person-date--year';

    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Function for the elements within the AfterbuildAge.
   */
  public static function afterBuildAge(array $element, FormStateInterface $form_state) {

    // Add #states targeting the specific element and table row.
    $composite_name = $element['#parents'][0];

    $element['#states']['visible'] = [
      [':input[name="' . $composite_name . '[select_dob_or_age]"]' => ['value' => 'No']],
    ];
    $element['#states']['required'] = [
      [':input[name="' . $composite_name . '[select_dob_or_age]"]' => ['value' => 'No']],
    ];

    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Function for the Date of birth afterbuild container.
   */
  public static function afterBuildDobContainer(array $element, FormStateInterface $form_state) {

    $composite_name = $element['#parents'][0];

    $element['#states']['visible'] = [
      [':input[name="' . $composite_name . '[select_dob_or_age]"]' => ['value' => 'Yes']],
    ];

    $element['date_of_birth']['day']['#states']['required'] = [
      [':input[name="' . $composite_name . '[select_dob_or_age]"]' => ['value' => 'Yes']],
    ];
    $element['date_of_birth']['month']['#states']['required'] = [
      [':input[name="' . $composite_name . '[select_dob_or_age]"]' => ['value' => 'Yes']],
    ];
    $element['date_of_birth']['year']['#states']['required'] = [
      [':input[name="' . $composite_name . '[select_dob_or_age]"]' => ['value' => 'Yes']],
    ];
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
    // 3) radio button select_dob_or_age must be selected
    // 4) if age radio then a value for age is needed -.
    // but this is handled using clientside validation
    // 5) if dob radio then a value for dob is needed -.
    // but this is handled using clientside validation.
    // 1) first_name is needed.
    if (empty($value['first_name'])) {
      $form_state->setErrorByName('first_name', "Please provide a first name");
    }

    // 2) last_name is needed
    if (empty($value['last_name'])) {
      $form_state->setErrorByName('last_name', "Please provide a last name");
    }

    // 3) radio button select_dob_or_age must be selected
    // NOTE DOB or Age are also required but.
    // this is handles with client site validation
    if (empty($value['select_dob_or_age'])) {
      $form_state->setErrorByName('select_dob_or_age', "Please provide a date of birth or age.");
    }
  }

}
