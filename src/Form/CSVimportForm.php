<?php
/**
 * @file
 * Contains \Drupal\csvimport\Form\CSVimportForm.
 */

namespace Drupal\csvimport\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Bytes;

/**
 * Implements the ajax demo form controller.
 *
 * This example demonstrates using ajax callbacks to populate the options of a
 * color select element dynamically based on the value selected in another
 * select element in the form.
 *
 * @see \Drupal\Core\Form\FormBase
 * @see \Drupal\Core\Form\ConfigFormBase
 */
class CSVimportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'csvimport_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#attributes'] = [
      'enctype' => 'multipart/form-data',
    ];
    $form['csvfile']     = [
      '#title'       => t('CSV File'),
      '#type'        => 'file',
      '#description' => ($max_size = file_upload_max_size()) ? t('Due to server restrictions, the <strong>maximum upload file size is @max_size</strong>. Files that exceed this size will be disregarded.', ['@max_size' => format_size($max_size)]) : '',
    ];
    $form['submit']      = [
      '#type'  => 'submit',
      '#value' => t('Commence Import'),
    ];
    $form['#validate']   = [
      'csvimport_validate_fileupload',
      'csvimport_form_validate',
    ];
    return $form;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Final submit
  }
}