<?php

declare(strict_types = 1);

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
class PersonComponentNoNameTest extends BrowserTestBase {

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
  public function testPersonComponentNoNameConditionallyRequiredSubelement() :void {

    // Load the form.
    $this->drupalGet('/webform/no_name__conditionally_req_sub');

    // Fill in the date element and then proceed to submit the page.
    $form_values = [
      'first_name_required'   => '1',
    ];
    $this->submitForm($form_values, 'Submit');

    // Assert that the error messages are present.
    $this->assertSession()->pageTextContains('Please provide a first name.');

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
