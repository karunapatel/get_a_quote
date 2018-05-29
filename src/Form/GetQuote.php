<?php

namespace Drupal\get_a_quote\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the GetQuote form.
 */
class GetQuote extends FormBase {

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
    return 'get_quote_form';  
  }  
  
  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
  
    $form['quotefieldset'] = [
	  '#type' => 'fieldset',
	];
	
	$form['quotefieldset']['your_name'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Your Name'),
	  '#required' => TRUE,
    ];

    $form['quotefieldset']['email'] = [  
      '#type' => 'email',  
      '#title' => $this->t('Email'),  
      '#required' => TRUE,	  
    ];
	
	$form['quotefieldset']['number'] = array (
      '#type' => 'tel',
      '#title' => t('Mobile no'),
	  '#required' => TRUE,
    );
	
	$form['quotefieldset']['quote_subject'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Subject'),
	  '#required' => TRUE,
    ];
	
	$form['quotefieldset']['quote_description'] = [  
      '#type' => 'textarea',  
      '#title' => $this->t('Description'),
	  '#required' => TRUE,
    ];
	
	$form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send Quote'),
      '#button_type' => 'primary',
    );

    return $form;  
  }  
  
  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      if (strlen($form_state->getValue('number')) < 10) {
        $form_state->setErrorByName('number', $this->t('Mobile number is too short.'));
      }
    }
  
  /**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {  
//  parent::submitForm($form, $form_state);  
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }
	
	$mailManager = \Drupal::service('plugin.manager.mail');
	$module = 'get_a_quote';
	$key = 'send_quote';
	$to = \Drupal::currentUser()->getEmail();
	$params['message'] = $form_state->getValue('quote_description');
	$params['subject'] = $form_state->getValue('quote_subject');
	$langcode = \Drupal::currentUser()->getPreferredLangcode();
	$send = true;
	$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	if ($result['result'] !== true) {
	  drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
	}
	else {
	  drupal_set_message(t('Your message has been sent.'));
	}
    //$this->config('quote.settings')  
      //->set('enable_quote_for_commerce_product', $form_state->getValue('enable_quote_for_commerce_product'))  
      //->save();  
  }  
}
