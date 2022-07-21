<?php

namespace Drupal\layout_builder_asset\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class LayoutBuilderAssetCssForm.
 *
 * @package Drupal\layout_builder_asset\Form
 */
class LayoutBuilderAssetCssForm extends LayoutBuilderAssetFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['code']['#attributes']['data-ace-mode'] = 'css';
    return $form;
  }

}
