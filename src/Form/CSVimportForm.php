<?php
/**
 * @file
 * Contains \Drupal\csvimport\Form\CSVimportForm.
 */

namespace Drupal\csvimport\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

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

    $form['csvfile'] = [
      '#title'            => t('CSV File'),
      '#type'             => 'file',
      '#description'      => ($max_size = file_upload_max_size()) ? t('Due to server restrictions, the <strong>maximum upload file size is @max_size</strong>. Files that exceed this size will be disregarded.', ['@max_size' => format_size($max_size)]) : '',
      '#element_validate' => ['::csvimport_validate_fileupload'],
    ];

    $form['submit'] = [
      '#type'  => 'submit',
      '#value' => t('Start Import'),
    ];

    return $form;
  }

  /**
   * Validate the file upload. It must be a CSV, and we must
   * successfully save it to our import directory.
   */
  public static function csvimport_validate_fileupload(&$element, FormStateInterface $form_state, &$complete_form) {

    $validators = [
      'file_validate_extensions' => ['csv CSV'],
    ];

    if ($file = file_save_upload('csvfile', $validators, FALSE, 0, FILE_EXISTS_REPLACE)) {
      // The file was saved using file_save_upload() and was added to
      // the files table as a temporary file. We'll make a copy and let
      // the garbage collector delete the original upload.
      $csv_dir          = 'temporary://csvfile';
      $directory_exists = file_prepare_directory($csv_dir, FILE_CREATE_DIRECTORY);

      if ($directory_exists) {
        $destination = $csv_dir . '/' . $file->getFilename();
        if (file_copy($file, $destination, FILE_EXISTS_REPLACE)) {
          $form_state->setValue('csvupload', $destination);
        }
        else {
          $form_state->setErrorByName('csvimport', t('Unable to copy upload file to @dest', ['@dest' => $destination]));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if ($csvupload = $form_state->getValue('csvupload')) {
      if ($handle = fopen($csvupload, 'r')) {
        if ($line = fgetcsv($handle, 4096)) {
          /**
           * Validate the uploaded CSV here.
           *
           * The example CSV happens to have cell A1 ($line[0]) as
           * below; we validate it only.
           *
           * You'll probably want to check several headers, eg:
           *   if ( $line[0] == 'Index' || $line[1] != 'Supplier' || $line[2] != 'Title' )
           */
          if ($line[0] != 'Example CSV for csvimport.module - http://github.com/xurizaemon/csvimport') {
            $form_state->setErrorByName('csvfile', t('Sorry, this file does not match the expected format.'));
          }
        }
        fclose($handle);
      }
      else {
        $form_state->setErrorByName('csvfile', t('Unable to read uploaded file @filepath', ['@filepath' => $csvupload]));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $batch = [
      'title'            => t('Importing CSV ...'),
      'operations'       => [],
      'init_message'     => t('Commencing'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished'         => 'csvimport_import_finished',
    ];
    if ($form_state->getValue('csvupload')) {
      if ($handle = fopen($form_state->getValue('csvupload'), 'r')) {
        $batch['operations'][] = [
          '_csvimport_remember_filename',
          [$form_state->getValue('csvupload')],
        ];
        $line_count            = 1;
        $first                 = TRUE;
        $line                  = fgetcsv($handle, 4096);
        while ($line = fgetcsv($handle, 4096)) {
          /**
           * we use base64_encode to ensure we don't overload the batch
           * processor by stuffing complex objects into it
           */
          $batch['operations'][] = [
            '_csvimport_import_line',
            [array_map('base64_encode', $line)],
          ];
        }
        fclose($handle);
      } // we caught this in csvimport_form_validate()
    } // we caught this in csvimport_form_validate()
    batch_set($batch);
  }

}
