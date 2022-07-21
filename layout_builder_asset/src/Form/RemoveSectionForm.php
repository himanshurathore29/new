<?php

namespace Drupal\layout_builder_asset\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\layout_builder\Form\RemoveSectionForm as OriginalRemoveSectionForm;
use Drupal\layout_builder\LayoutTempstoreRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * {@inheritdoc}
 */
class RemoveSectionForm extends OriginalRemoveSectionForm {

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(LayoutTempstoreRepositoryInterface $layout_tempstore_repository, PrivateTempStoreFactory $tempStoreFactory) {
    parent::__construct($layout_tempstore_repository);
    $this->tempStoreFactory = $tempStoreFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('layout_builder.tempstore_repository'),
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->handleLayoutBuilderAssetStorage();
    parent::submitForm($form, $form_state);
  }

  /**
   * Delete all the Asset Config entity of the Section.
   */
  protected function handleLayoutBuilderAssetStorage() {
    $components = $this->sectionStorage->getSection($this->delta)->getComponents();
    $store = $this->tempStoreFactory->get('layout_builder_asset');
    $ids = !empty($store->get('deleted')) ? $store->get('deleted') : [];
    $flag = FALSE;
    foreach ($components as $component) {
      if ($flag = !empty($component->get('additional'))) {
        $id = NULL;
        if ($component->get('layout_builder_asset_js')['id']) {
          $id = $component->get('layout_builder_asset_js')['id'];
        }
        elseif ($component->get('layout_builder_asset_style')['id']) {
          $id = $component->get('layout_builder_asset_style')['id'];
        }
        $ids[] = $id;
      }
    }
    $flag && $store->set('deleted', $ids);
  }

}
