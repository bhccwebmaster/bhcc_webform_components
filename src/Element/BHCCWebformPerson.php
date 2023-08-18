<?php

namespace Drupal\bhcc_webform_components\Element;

use Drupal\bhcc_webform\BHCCWebformHelper;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\webform\Element\WebformCompositeBase;

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
 * @FormElement("bhcc_webform_person")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\bhcc_webform_components\Element\BHCCWebformPersonExample
 */
class BHCCWebformPerson extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {

    // Generate a unique ID that can be used by #states.
    $html_id = Html::getUniqueId('bhcc_webform_person');

    // @todo check with Andies that weight setting is correct?
    $elements['title_options'] = [
      '#type' => 'select',
      '#title' => t('Title options'),
      '#options' => 'bhcc_title',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--title',
        // @todo check naming conventions here - use hyphen instead of underscore?
        'class' => ['bhcc-webform-person--title'],
      ],

    ];

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

    $elements['middle_name'] = [
      '#type' => 'textfield',
      '#title' => t('Middle name'),
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--middle_name',
        // TO DO - check styling requirements.
        'class' => ['bhcc-webform-person--middle_name'],
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
        'data-webform-composite-id' => $html_id . '--date_of_birth',
        'class' => ['bhcc-webform-person--date_of_birth'],
      ],
      // --new code below
      '#date_date_min' => '01/01/1900',
      '#date_date_max' => 'today',
      '#date_part_order' => ['day', 'month', 'year'],
      '#date_text_parts' => ['day', 'month', 'year'],
      '#description' => t('For example 08/02/1982'),
      '#required_error' => 'Please provide a date of birth.',
    ];

    // Get the international phone code.
    $elements['country_code_options'] = [
      '#type' => 'select',
      '#title' => t('Country dialling code'),
      '#options' => 'bhcc_international_phone_codes',
      '#empty_option' => '+44 (United Kingdom)',
      '#empty_value' => '+44 (United Kingdom)',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--country_code',
        // @todo check naming conventions here - use hyphen instead of underscore?
        'class' => ['bhcc-webform-person--country_code'],
      ],
    ];

    $elements['uk_mobile_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Mobile phone'),
      '#pattern' => "^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$",
      '#pattern_error' => "Please enter a valid mobile number",
      '#after_build' => [[get_called_class(), 'afterBuildUkPhones']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--uk_mobile_phone',
        'class' => ['bhcc-webform-person--mobile_phone'],
      ],

    ];
    $elements['uk_landline_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Landline phone'),
      '#pattern' => "(\(?\+44\)?\s?(1|2|3|7|8)\d{3}|\(?(01|02|03|07|08)\d{3}\)?)\s?\d{3}\s?\d{3}|(\(?\+44\)?\s?(1|2|3|5|7|8)\d{2}|\(?(01|02|03|05|07|08)\d{2}\)?)\s?\d{3}\s?\d{4}|(\(?\+44\)?\s?(5|9)\d{2}|\(?(05|09)\d{2}\)?)\s?\d{3}\s?\d{3}",
      '#pattern_error' => "Please enter a valid landline number",
      '#after_build' => [[get_called_class(), 'afterBuildUkPhones']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--uk_landline_phone',
        'class' => ['bhcc-webform-person--landline_phone'],
      ],
    ];

    $elements['international_mobile_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Mobile phone'),
      '#after_build' => [[get_called_class(), 'afterBuildInternationalPhones']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--international_mobile_phone',
        'class' => ['bhcc-webform-person--mobile_phone'],
      ],

    ];
    $elements['international_landline_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Landline phone'),
      '#after_build' => [[get_called_class(), 'afterBuildInternationalPhones']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--international_landline_phone',
        'class' => ['bhcc-webform-person--landline_phone'],
      ],
    ];

    $elements['email_address'] = [
      '#type' => 'textfield',
      '#title' => t('Email address'),
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--email_address',
        'class' => ['bhcc-webform-person--email_address'],
      ],
      '#pattern' => "^\w+([\'.-]?\w+)*@\w+([\'.-]?\w+)*(\.\w{2,16})+$",
    ];

    $elements['national_insurance_number'] = [
      '#type' => 'textfield',
      '#title' => t('National insurance number'),
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--national_insurance_number',
        'class' => ['bhcc-webform-person--national_insurance_number'],
      ],
      '#description' => t('eg. NA123456A'),
      '#pattern' => "^([a-zA-Z]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([a-zA-Z]){1}?$",
    ];

    $elements['relationship_to_you'] = [
      '#type' => 'webform_select_other',
      '#title' => t('Relationship to you'),
      '#options' => 'bhcc_relationship_to_you',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--relationship_to_you',
        // @todo check naming conventions here - use hyphen instead of underscore?
        'class' => ['bhcc-webform-person--relationship_to_you'],
      ],
    ];

    $elements['organisation'] = [
      '#type' => 'textfield',
      '#title' => t('Organisation'),
      '#after_build' => [[get_called_class(), 'afterBuildOrganisation']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--organisation',
        'class' => ['bhcc-webform-person--organisation'],
      ],
    ];

    $elements['detail'] = [
      '#type' => 'textfield',
      '#title' => t('Please provide details below'),
      '#after_build' => [[get_called_class(), 'afterBuildDetail']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--detail',
        'class' => ['bhcc-webform-person--detail'],
      ],
    ];

    return $elements;
  }

  /**
   * Alter the organisation element after it has been built.
   */
  public static function afterBuildOrganisation(array $element, FormStateInterface $form_state) {

    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];

    $element['#states']['visible'] = [
      ':input[name="' . $composite_name . '[relationship_to_you][select]"]' => [
        ['value' => 'Manager/employer'],
        ['or'],
        ['value' => 'Colleague'],
        ['or'],
        ['value' => 'Staff/employee'],
      ],
    ];

    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Alter the date element after it has been built.
   */
  public static function afterBuildDate(array $element, FormStateInterface $form_state) {
    // $element = parent::afterBuild($element, $form_state);
    // set the property of the date of birth elements
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
   * Alter the UK phone elements after they have been built.
   */
  public static function afterBuildUkPhones(array $element, FormStateInterface $form_state) {

    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];

    $element['#states']['visible'] = [
      ':input[name="' . $composite_name . '[country_code_options]"]' => [
        // @todo sort out the issue of why no + sign appearing and then amend below
        ['value' => '+44 (United Kingdom)'],
        ['or'],
    // To allow for default of - None - in select drop down.
        ['value' => ''],
      ],
    ];

    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Alter the international phone elements after they have been built.
   */
  public static function afterBuildInternationalPhones(array $element, FormStateInterface $form_state) {

    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];

    $element['#states']['invisible'] = [
      ':input[name="' . $composite_name . '[country_code_options]"]' => [
        // @todo sort out the issue of why no + sign appearing and then amend below
        ['value' => '+44 (United Kingdom)'],
        ['or'],
    // To allow for default of - None - in select drop down.
        ['value' => ''],
      ],
    ];

    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function validateWebformComposite(&$element, FormStateInterface $form_state, &$complete_form) {

    $value = NestedArray::getValue($form_state->getValues(), $element['#parents']);
    $element_key = end($element['#parents']);

    // If not visible - don't validate.
    if (!Element::isVisibleElement($element)) {
      return;
    }

    // If the element or any of its parent containers are hidden by conditions,
    // Bypass validation and clear any required element errors generated
    // for this element.
    $limit_validation_errors = $form_state->getLimitValidationErrors();
    if (!BHCCWebformHelper::isElementVisibleThroughParent($element, $form_state, $complete_form)) {  
      // Only clear and reset errors if there are no limit validation errors keys set.
      if (is_null($limit_validation_errors)) {
        $form_errors = $form_state->getErrors();
        $form_state->clearErrors();
        foreach ($form_errors as $error_key => $error_value) {
          if (strpos($error_key, $element_key . ']') !== 0) {
            $form_state->setErrorByName($error_key, $error_value);
          }
        }
      }
      return;
    }

    // Otherwise deal with any validation thats needed
    // 1) first_name is needed
    // 2) last_name is needed.
    // 1) first_name is needed.
    if (empty($value['first_name'])) {
      $form_state->setErrorByName('first_name', "Please provide a first name");
    }

    // 2) last_name is needed
    if (empty($value['last_name'])) {
      $form_state->setErrorByName('last_name', "Please provide a last name");
    }

    // A work around for bug whereby if date_of_birth.
    // is only shown conditionally
    // any 'required' setting will be ignored.
    if (!empty($element['#date_of_birth___required'])) {
      // If required is set to true and the DOB field is empty - flag error.
      if ($element['#date_of_birth___required'] && empty($value['date_of_birth'])) {
        $form_state->setErrorByName('date_of_birth', "Date of birth is empty or invalid");
      }
    }
  }

  /**
   * Alter the details element after it has been built.
   */
  public static function afterBuildDetail(array $element, FormStateInterface $form_state) {

    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];

    $element['#states']['visible'] = [
      ':input[name="' . $composite_name . '[relationship_to_you][select]"]' => [
        ['value' => 'Family'],
        ['or'],
        ['value' => 'Professional'],
      ],
    ];

    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

}
