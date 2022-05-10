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
 * @FormElement("bhcc_webform_person_requires_contact_method")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\bhcc_webform_components\Element\BHCCWebformPersonExample
 */
class BHCCWebformPerson_RequiresContactMethod extends WebformCompositeBase
{

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element)
  {

    // Generate a unique ID that can be used by #states.
    $html_id = Html::getUniqueId('bhcc_webform_person_requires_contact_method');

    //@todo check with Andies that weight setting is correct?
    $elements['title_options'] = [
      '#type' => 'select',
      '#title' => t('Title options'),
      '#options' => 'bhcc_title',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--title',
        //@todo check naming conventions here - use hyphen instead of underscore?
        'class' => ['bhcc-webform-person--title'],
      ],

    ];

    $elements['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#required_error' => 'Please provide a first name.',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--first_name',
        //TO DO - check styling requirements
        'class' => ['bhcc-webform-person--first_name'],
      ],
    ];

    $elements['middle_name'] = [
      '#type' => 'textfield',
      '#title' => t('Middle name'),
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--middle_name',
        //TO DO - check styling requirements
        'class' => ['bhcc-webform-person--middle_name'],
      ],
    ];
    $elements['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last name'),
      '#required_error' => 'Please provide a last name.',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--last_name',
        //TO DO - check styling requirements
        'class' => ['bhcc-webform-person--last_name'],
      ],
    ];


    $elements['date_of_birth'] = [
      '#type' => 'datelist',
      '#title' => t('Date of birth'),
      '#after_build' => [[get_called_class(), 'afterBuild_Date']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--date_of_birth',
        'class' => ['bhcc-webform-person--date_of_birth'],
      ],
      '#date_date_min' => '01/01/1900',
      '#date_date_max' => 'today',
      '#date_part_order' => ['day', 'month', 'year'],
      '#date_text_parts' => ['day', 'month', 'year'],
      '#description' => 'For example 08/02/1982',
    ];

    // get the international phone code
    $elements['country_code_options'] = [
      '#type' => 'select',
      '#title' => t('Country dialling code'),
      '#options' => 'bhcc_international_phone_codes',
      '#empty_option' => '+44 (United Kingdom)',
      '#empty_value' => '+44 (United Kingdom)',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--country_code',
        //@todo check naming conventions here - use hyphen instead of underscore?
        'class' => ['bhcc-webform-person--country_code'],
      ],
    ];

    $elements['uk_mobile_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Mobile phone'),
      '#pattern' => "^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$",
      '#pattern_error' => "Please enter a valid mobile number",
      '#required_error' => "Please enter either mobile number, landline number or email address",
      '#after_build' => [
        [get_called_class(), 'afterBuild_UKPhones'],
        //[get_called_class(), 'afterBuild_ContactMethodRequired'],
      ],
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
      '#required_error' => "Please enter either mobile number, landline number or email address",
      '#after_build' => [
        [get_called_class(), 'afterBuild_UKPhones'],
        //[get_called_class(), 'afterBuild_ContactMethodRequired'],
      ],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--uk_landline_phone',
        'class' => ['bhcc-webform-person--landline_phone'],
      ],
    ];


    $elements['international_mobile_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Mobile phone'),
      '#required_error' => "Please enter either mobile number, landline number or email address",
      '#after_build' => [
        [get_called_class(), 'afterBuild_InternationalPhones'],
        //[get_called_class(), 'afterBuild_ContactMethodRequired'],
      ],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--international_mobile_phone',
        'class' => ['bhcc-webform-person--mobile_phone'],
      ],

    ];
    $elements['international_landline_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Landline phone'),
      '#required_error' => "Please enter either mobile number, landline number or email address",
      '#after_build' => [
        [get_called_class(), 'afterBuild_InternationalPhones'],
        //[get_called_class(), 'afterBuild_ContactMethodRequired'],
      ],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--international_landline_phone',
        'class' => ['bhcc-webform-person--landline_phone'],
      ],
    ];


    $elements['email_address'] = [
      '#type' => 'textfield',
      '#title' => t('Email address'),
      '#required_error' => "Please enter either mobile number, landline number or email address",
      '#after_build' => [
        //[get_called_class(), 'afterBuild_ContactMethodRequired'],
      ],
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
      '#description' => 'eg. NA123456A',
      '#pattern' => "^([a-zA-Z]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([a-zA-Z]){1}?$",
    ];

    $elements['relationship_to_you'] = [
      '#type' => 'webform_select_other',
      '#title' => t('Relationship to you'),
      '#options' => 'bhcc_relationship_to_you',
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--relationship_to_you',
        //@todo check naming conventions here - use hyphen instead of underscore?
        'class' => ['bhcc-webform-person--relationship_to_you'],
      ],
    ];

    $elements['organisation'] = [
      '#type' => 'textfield',
      '#title' => t('Organisation'),
      '#after_build' => [[get_called_class(), 'afterBuild_Organisation']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--organisation',
        'class' => ['bhcc-webform-person--organisation'],
      ],
    ];

    $elements['detail'] = [
      '#type' => 'textfield',
      '#title' => t('Please provide details below'),
      '#after_build' => [[get_called_class(), 'afterBuild_Detail']],
      '#attributes' => [
        'data-webform-composite-id' => $html_id . '--detail',
        'class' => ['bhcc-webform-person--detail'],
      ],
    ];

    return $elements;
  }



  public static function afterBuild_Organisation(array $element, FormStateInterface $form_state)
  {

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

  public static function afterBuild_Date(array $element, FormStateInterface $form_state)
  {
    //$element = parent::afterBuild($element, $form_state);

    //set the property of the date of birth elements
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

  public static function afterBuild_UKPhones(array $element, FormStateInterface $form_state)
  {

    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];

    $element['#states']['visible'] = [
      ':input[name="' . $composite_name . '[country_code_options]"]' => [
        ['value' => '+44 (United Kingdom)'],
        ['or'],
        ['value' => ''], //to allow for default of - None - in select drop down
      ],
    ];

    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  public static function afterBuild_InternationalPhones(array $element, FormStateInterface $form_state)
  {

    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];

    $element['#states']['invisible'] = [
      ':input[name="' . $composite_name . '[country_code_options]"]' => [
        ['value' => '+44 (United Kingdom)'],
        ['or'],
        ['value' => ''], //to allow for default of - None - in select drop down
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
  public static function validateWebformComposite(&$element, FormStateInterface $form_state, &$complete_form)
  {

    //@todo do we need validateDatelist (above) now with the fix below ????? 22/10/2021
    //dpm($element);
    $value = NestedArray::getValue($form_state->getValues(), $element['#parents']);
    $element_key = end($element['#parents']);

    // if not visible - don't validate
    if (!Element::isVisibleElement($element)) {
      return;
    }

    // If the element or any of its parent containers are hidden by conditions,
    // Bypass validation and clear any required element errors generated
    // for this element.
    if (!BHCCWebformHelper::isElementVisibleThroughParent($element, $form_state, $complete_form)) {
      //\Drupal::messenger()->addStatus(t('its NOT visible"'), 'status');
      $form_errors = $form_state->getErrors();
      $form_state->clearErrors();
      foreach ($form_errors as $error_key => $error_value) {
        if (strpos($error_key, $element_key . ']') !== 0) {
          $form_state->setErrorByName($error_key, $error_value);
        }
      }
      return;
    }

    // otherwise deal with any validation thats needed
    // 1) first_name is needed
    // 2) last_name is needed

    // 1) first_name is needed
    if (empty($value['first_name'])) {
      $form_state->setErrorByName('first_name', "Please provide a first name");
    }

    // 2) last_name is needed
    if (empty($value['last_name'])) {
      $form_state->setErrorByName('last_name', "Please provide a last name");
    }

    if (
      empty($value['uk_mobile_phone'])
      && empty($value['uk_landline_phone'])
      && empty($value['international_mobile_phone'])
      && empty($value['international_landline_phone'])
      && empty($value['email_address'])
    ) {

      $form_state->setErrorByName('uk_mobile_phone', "Please provide either a phone number or email address");
      $form_state->setErrorByName('international_mobile_phone', "Please provide either a phone number or email address");
    }

    // A work around for bug whereby if date_of_birth is only shown conditionally
    // any 'required' setting will be ignored.
    // '#date_of_birth___required' is only set when true @todo test this out
    if (isset($element['#date_of_birth___required']) && empty($value['date_of_birth'])) {
      $form_state->setErrorByName('date_of_birth', "Date of birth is empty or invalid");
    }
  }

  public static function afterBuild_Detail(array $element, FormStateInterface $form_state)
  {

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
