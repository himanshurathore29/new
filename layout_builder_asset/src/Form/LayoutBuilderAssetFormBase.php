<?php

namespace Drupal\layout_builder_asset\Form;

use Psr\Log\LoggerInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\PluginFormFactoryInterface;
use Drupal\Core\Executable\ExecutableManagerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\Context\ContextRepositoryInterface;

/**
 * Class AssetInjectorCsssForm.
 *
 * @package Drupal\asset_injector\Form
 */
class LayoutBuilderAssetFormBase extends EntityForm {
  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;
  /**
   * The Asset entity.
   *
   * @var \Drupal\layout_builder_asset\LayoutBuilderInterface
   */
  protected $entity;
  /**
   * The condition plugin manager.
   *
   * @var \Drupal\Core\Condition\ConditionManager
   */
  protected $manager;
  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;
  /**
   * The language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $language;
  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandler
   */
  protected $themeHandler;
  /**
   * The context repository service.
   *
   * @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface
   */
  protected $contextRepository;
  /**
   * The plugin form manager.
   *
   * @var \Drupal\Core\Plugin\PluginFormFactoryInterface
   */
  protected $pluginFormFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('logger.factory')->get('layout_builder_asset'),
          $container->get('plugin.manager.condition'),
          $container->get('context.repository'),
          $container->get('language_manager'),
          $container->get('theme_handler'),
          $container->get('plugin_form.factory'));
  }

  /**
   * LayoutBuilderInterface constructor.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Executable\ExecutableManagerInterface $manager
   *   The ConditionManager for building the conditions UI.
   * @param \Drupal\Core\Plugin\Context\ContextRepositoryInterface $context_repository
   *   The lazy context repository service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language
   *   The language manager.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler.
   * @param \Drupal\Core\Plugin\PluginFormFactoryInterface $plugin_form_manager
   *   The plugin form manager.
   */
  public function __construct(LoggerInterface $logger, ExecutableManagerInterface $manager, ContextRepositoryInterface $context_repository, LanguageManagerInterface $language, ThemeHandlerInterface $theme_handler, PluginFormFactoryInterface $plugin_form_manager) {
    $this->logger = $logger;
    $this->manager = $manager;
    $this->contextRepository = $context_repository;
    $this->language = $language;
    $this->themeHandler = $theme_handler;
    $this->pluginFormFactory = $plugin_form_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['#tree'] = TRUE;
    // Store the gathered contexts in the form state for other objects to use
    // during form building.
    $form_state->setTemporaryValue('gathered_contexts', $this->contextRepository->getAvailableContexts());
    /** @var \Drupal\layout_builder_asset\Entity\LyoutBuilderCssBase $entity */
    $entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $entity->label(),
      '#description' => $this->t('Label for the @type.', [
        '@type' => $entity->getEntityType()->getLabel(),
      ]),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#machine_name' => [
        'exists' => '\\' . $entity->getEntityType()->getClass() . '::load',
        'replace_pattern' => '[^a-z0-9_]+',
        'replace' => '_',
      ],
      '#disabled' => !$entity->isNew(),
    ];
    $form['code'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Code'),
      '#description' => $this->t('The actual code goes in here.'),
      '#rows' => 10,
      '#default_value' => $entity->code,
      '#required' => FALSE,
    ];
    $form['class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Class'),
      '#description' => $this->t('Add Unique Class for the Block to apply the css specifically to the current context or keep empty to use the default config block.'),
      '#default_value' => $entity->class,
      '#attributes' => ['disabled' => TRUE],
    ];
    $form['#attached']['library'][] = 'layout_builder_asset/ace-editor';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->logger('layout_builder_asset')->info('Enters Submit form');
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = $entity->save();

    switch ($status) {
      case SAVED_NEW:
        $message = $this->t('Created the %label Layout Builder Css.', [
          '%label' => $entity->label(),
        ]);
        $log = '%type asset %id created';
        break;

      default:
        $message = $this->t('Saved the %label Layout Builder Css Injector.', [
          '%label' => $entity->label(),
        ]);
        $log = '%type asset %id saved';
    }
    $this->messenger()->addMessage($message);
    $this->logger->notice($log, [
      '%type' => $entity->getEntityTypeId(),
      '%id' => $entity->id(),
    ]);

    $trigger = $form_state->getTriggeringElement();
    if (isset($trigger['#name']) && $trigger['#name'] != 'save_continue') {
      // $form_state->setRedirectUrl($entity->toUrl('collection'));
    }
    else {
      $form_state->setRedirectUrl($entity->toUrl());
    }
  }

}
