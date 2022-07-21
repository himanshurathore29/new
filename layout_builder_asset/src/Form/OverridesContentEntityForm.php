<?php

namespace Drupal\layout_builder_asset\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\layout_builder\Form\OverridesEntityForm;
use Drupal\layout_builder\LayoutTempstoreRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * On saving the layout, creating the asset entity.
 */
class OverridesContentEntityForm extends OverridesEntityForm {

  /**
   * Layout Builder Asset JS storage entity manager.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $assetJs;

  /**
   * Layout Builder Asset CSS storage entity manager.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $assetCss;

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * Constructs a new OverridesEntityForm.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\layout_builder\LayoutTempstoreRepositoryInterface $layout_tempstore_repository
   *   The layout tempstore repository.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempStoreFactory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityManager
   *   The Entity Manager Interface.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info, TimeInterface $time, LayoutTempstoreRepositoryInterface $layout_tempstore_repository, PrivateTempStoreFactory $tempStoreFactory, EntityTypeManagerInterface $entityManager) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time, $layout_tempstore_repository);
    $this->tempStoreFactory = $tempStoreFactory;
    $this->entityTypeManager = $entityManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('layout_builder.tempstore_repository'),
      $container->get('tempstore.private'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $layoutBuilder = $this->entity->get('layout_builder__layout')->getValue();
    $this->assetCss = $this->entityTypeManager->getStorage('layout_builder_asset_css');
    $this->assetJs = $this->entityTypeManager->getStorage('layout_builder_asset_js');
    // Deleting any asset if needed.
    $this->deleteAsset();
    foreach ($layoutBuilder as $section) {
      $section = $section['section'];
      foreach ($section->getComponents() as $component) {
        if (!empty($component->get('additional')['layout_builder_asset_style'])) {
          // Updated the CSS Asset entity.
          if ($layoutBuilderAssetCss = $this->assetCss->load($component->get('additional')['layout_builder_asset_style']['id'])) {
            $layoutBuilderAssetCss->code = $component->get('additional')['layout_builder_asset_style']['code'];
            $layoutBuilderAssetCss->label = $component->get('additional')['layout_builder_asset_style']['label'];
            $layoutBuilderAssetCss->class = $component->get('additional')['layout_builder_asset_style']['class'];
            $layoutBuilderAssetCss->save();
          }
          // Create the CSS Asset entity.
          else {
            $layout_builder_asset = $this->assetCss->create($component->get('additional')['layout_builder_asset_style']);
            $layout_builder_asset->save();
          }
        }
        if (!empty($component->get('additional')['layout_builder_asset_js'])) {
          // Updated the JS Asset entity.
          if ($layoutBuilderAssetJs = $this->assetJs->load($component->get('additional')['layout_builder_asset_js']['id'])) {
            $layoutBuilderAssetJs->code = $component->get('additional')['layout_builder_asset_js']['code'];
            $layoutBuilderAssetJs->label = $component->get('additional')['layout_builder_asset_js']['label'];
            $layoutBuilderAssetJs->class = $component->get('additional')['layout_builder_asset_js']['class'];
            $layoutBuilderAssetJs->save();
          }
          // Create the Js Asset entity.
          else {
            $layout_builder_asset = $this->assetJs->create($component->get('additional')['layout_builder_asset_js']);
            $layout_builder_asset->save();
          }
        }
      }
    }
    return parent::save($form, $form_state);;
  }

  /**
   * Delete all the Assets for the layout builder.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  protected function deleteAsset() {
    $store = $this->tempStoreFactory->get('layout_builder_asset');
    if (!empty($store->get('deleted'))) {

      // Delete both the entity (css and js).
      // Both has the same id.
      $entitiesName = ['layout_builder_asset_js', 'layout_builder_asset_css'];
      if ($ids = $store->get('deleted')) {
        foreach ($ids as $id) {
          foreach ($entitiesName as $entityName) {
            $assetEntity = $this->entityTypeManager->getStorage($entityName);
            $asset = $assetEntity->load($id);
            $asset && $asset->delete();
          }
        }
        $store->delete('deleted');
      }
    }
  }

}
