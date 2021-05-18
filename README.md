# Drupal 8 Batch Process CSV Import

## This is an example module!

- Demonstrates how to use file upload and Batch API to
import a CSV file.
- The batch iterates through each line of the CSV (except the first) and does
 ... nothing, except for filling the progress bar.
- Sample code to import CSV to node included.
- In the end it outputs a new CSV file containing all lines that were not
imported due to malformation (if any).

The basic idea is that if you're writing a CSV importer for Drupal,
maybe you can start here and save a lot of time.

## See also

You should definitely check Drupal's excellent [Migrate](https://www.drupal.org/docs/drupal-apis/migrate-api/migrate-api-overview)
functionality and the toolkit & features it offers. They may fit your needs better,
especially if you prefer using configuration over custom code.

In particular, take a look at [Migrate Source UI](https://www.drupal.org/project/migrate_source_ui)
which fills a similar space to this module; a web UI that you can throw CSV at.

If you want a lightweight start point which requires custom coding, this module may 
be useful and we hope you find it so!

## License

* GPLv2, per Drupal contrib.

## Code of conduct

* [DCoC](https://www.drupal.org/dcoc)
