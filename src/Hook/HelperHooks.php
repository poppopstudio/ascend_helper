<?php

declare(strict_types=1);

namespace Drupal\ascend_helper\Hook;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\BubbleableMetadata;

/**
 * Contains hook implementations for the Ascend Helper module.
 */
class HelperHooks {

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(&$form, FormStateInterface $form_state, $form_id) {
    // \Drupal::messenger()->addMessage(t("Form ID: @fid", ['@fid' => $form_id]));
    return;
  }

  /**
   * Implements hook_tokens_alter().
   */
  #[Hook('tokens_alter')]
  public function tokensAlter(array &$replacements, array $context, BubbleableMetadata $bubbleable_metadata) {
    // Convert term description token to string and strip html tags.
    // Without this, term desc is wrapped in a <p> tag.
    if ($context['type'] == 'term') {
      if (isset($replacements['[term:description]'])) {
        $desc = (string) $replacements['[term:description]'];
        $desc = strip_tags($desc);
        $replacements['[term:description]'] = $desc;
      }
    }
  }

  /**
   * Implements hook_ENTITY_TYPE_presave().
   */
  #[Hook('taxonomy_term_presave')]
  public function taxonomyTermPresave(EntityInterface $entity) {
    /**
     * Base field overrides required to set description text_format, BUT...
     * They don't work without the UI, so we have to force at save (import) time.
     * Might want to enforce for all vocabs not just category?
     */
    if ($entity->bundle() === 'category') {
      $description = $entity->description;
      if (!empty($description->value) && empty($description->format)) {
        $description->format = 'plain_text';
      }
    }
  }

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('form_taxonomy_term_category_edit_info_form_alter')]
  public function formTaxonomyTermCategoryEditInfoFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    // Hide term 'relations' element; don't allow alterations in this form mode.
    $form['relations']['#access'] = FALSE;
    return;
  }

  /**
   * Implements hook_page_attachments().
   */
  #[Hook('page_attachments')]
  public function pageAttachments(array &$attachments) {
    // Attach a CSS library to overwrite Gin styles.
    // Assumes the default theme has the library.
    // get the default theme.
    // $theme = \Drupal::config('system.theme')->get('default');
    // see ckeditor5.module

    // Attach the library if the active route is an admin one.
    if (\Drupal::service('router.admin_context')->isAdminRoute()) {
      // $attachments['#attached']['library'][] = 'your_module/your_library';
    }
  }

  /**
   * Implements hook_auto_username_alter().
   */
  #[Hook('auto_username_alter')]
  public function autoUsernameAlter(array &$data): void {
    // Force usernames to be all lower case.
    // $data['username'] = strtolower($data['username']);
  }
}
