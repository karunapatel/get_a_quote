<?php

namespace Drupal\get_a_quote\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the QuoteGeneralSettingsForm form.
 */
class QuoteGeneralSettingsForm extends ConfigFormBase {

  /**  
   * {@inheritdoc}  
   */  
  protected function getEditableConfigNames() {  
    return [  
      'quote.settings',  
    ];  
  }  

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
    return 'get_quote_settings_form';  
  }  
  
  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('quote.settings');  

    $form['enable_quote_for_commerce_product'] = [  
      '#type' => 'checkbox',  
      '#title' => $this->t('Enable Get a Quote for all commerce products'),  
      '#description' => $this->t('Gett a Quote button will be displayed on commerce checkout page.'),  
      '#default_value' => $config->get('enable_quote_for_commerce_product'),  
    ];  

    return parent::buildForm($form, $form_state);  
  }  
  
  /**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    parent::submitForm($form, $form_state);  

    $this->config('quote.settings')  
      ->set('enable_quote_for_commerce_product', $form_state->getValue('enable_quote_for_commerce_product'))  
      ->save();  
  }  
}
