<?php

namespace Drupal\layout_builder_asset\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\layout_builder\Form\RemoveBlockForm as OriginalRemoveBlockForm;
use Drupal\layout_builder\LayoutTempstoreRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * {@inheritdoc}
 */
class RemoveBlockForm extends OriginalRemoveBlockForm {

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
   * Create tempStorage for deleted assets.
   *
   * Mark the Asset Config entity of the Block as deleted,
   * so that it will be deleted while saving the layout.
   */
  protected function handleLayoutBuilderAssetStorage() {
    $component = $this->sectionStorage->getSection($this->delta)->getComponent($this->uuid);
    if (!empty($component->get('additional'))) {
      $store = $this->tempStoreFactory->get('layout_builder_asset');
      $ids = !empty($store->get('deleted')) ? $store->get('deleted') : [];
      if ($id = $component->get('layout_builder_asset_js')['id']) {
        $ids = [$id];
      }
      elseif ($id = $component->get('layout_builder_asset_style')['id']) {
        $ids = [$id];
      }
      $store->set('deleted', $ids);
    }
  }

}
