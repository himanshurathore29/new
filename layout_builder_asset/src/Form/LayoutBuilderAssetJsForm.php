<?php

namespace Drupal\layout_builder_asset\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class AssetInjectorJsForm.
 *
 * @package Drupal\asset_injector\Form
 */
class LayoutBuilderAssetJsForm extends LayoutBuilderAssetFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['code']['#attributes']['data-ace-mode'] = 'javascript';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    // Clear noscriptRegion if use_noscript is unchecked.
    if (!$form_state->getValue('use_noscript')) {
      $form_state->setValue('noscriptRegion', []);
    }
  }

}
