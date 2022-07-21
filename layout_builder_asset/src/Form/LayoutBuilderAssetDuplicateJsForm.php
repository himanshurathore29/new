<?php

namespace Drupal\layout_builder_asset\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class LayoutBuilderAssetDuplicateJsForm.
 *
 * @package Drupal\layout_builder_asset\Form
 */
class LayoutBuilderAssetDuplicateJsForm extends LayoutBuilderAssetJsForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $entity = $this->entity->createDuplicate();
    $entity->label = $this->t('Duplicate of @label', ['@label' => $this->entity->label()]);
    $this->entity = $entity;
    return parent::form($form, $form_state);
  }

}
