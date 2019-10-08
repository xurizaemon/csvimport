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

You should definitely check out the excellent Drupal Migrate module and the
toolkit & features it offers. They may fit your needs better. If you want a
lightweight start point, this module may be useful and we hope you find it so!

## License

* GPLv2, per Drupal contrib.

## Code of conduct

* [DCoC](https://www.drupal.org/dcoc)
