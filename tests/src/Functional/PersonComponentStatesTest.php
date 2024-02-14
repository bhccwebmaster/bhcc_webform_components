<?php

declare(strict_types=1);

namespace Drupal\Tests\bhcc_webform_components;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests bhcc_webform_person.
 *
 * Tests the BHCC person component in the context of a
 * webform with itself and other elements on the form
 * having various states and combinations of states e.g.
 * hidden, required, visible, invisible.
 */
class PersonComponentStatesTest extends BrowserTestBase {

  /**
   * This tests for a previous issue described below.
   *
   * A yes/no radio button displays the person component
   * and is a required element.  If the user doesn't make a
   * selection, an error should be display. But the person
   * components validation wiped errors, so that no error
   * is displayed.
   *
   * Test steps:
   *
   * Load the test form
   * Do NOT make a selection of radio button
   * Press submit
   * Assert that error message is shown
   */
  public function testHiddenPersonComponentHidingFormErrors() :void {

    // Load the form.
    $this->drupalGet('/webform/person_component__hidden_form');

    // Don't fill in the form, just try to submit.
    $form_values = [];
    $this->submitForm($form_values, 'Submit');

    // Assert that the error messages are present.
    $this->assertSession()->pageTextContains('Would you like to see the person component?');

    // Test that person component errors display.
    $form_values = [
      'show_person_component' => 'Yes',
    ];
    $this->submitForm($form_values, 'Submit');

    // Assert that the error messages are present.
    $this->assertSession()->pageTextContains('Please provide a first name');
    $this->assertSession()->pageTextContains('Please provide a last name');

    // Test person component can be submitted.
    $form_values = [
      'show_person_component' => 'Yes',
      'person_component[first_name]' => $this->randomMachineName(8),
      'person_component[last_name]' => $this->randomMachineName(8),
    ];
    $this->submitForm($form_values, 'Submit');

    // Assert that we reach the submission page.
    $this->assertSession()->pageTextContains('New submission added to Test hidden person component hiding other form errors.');

    // Test that not showing the person component does not show error messages.
    $this->drupalGet('/webform/person_component__hidden_form');
    $form_values = [
      'show_person_component' => 'No',
    ];
    $this->submitForm($form_values, 'Submit');

    // Assert we reach the submission page.
    $this->assertSession()->pageTextContains('New submission added to Test hidden person component hiding other form errors.');

  }

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bhcc_webform_components',
    'bhcc_webform_components_test',
    'bhcc_webform',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

}
