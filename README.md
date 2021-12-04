# Credit Info Package

This package is for Credit Info's API integration.

## Package functions

- Getting applicant's credit scores. However, if this information is not available;
- Getting applicant's full report.

## Prerequisites

- Composer (please visit [website](https://getcomposer.org/) to get the latest version of composer).
- PHP 8
- Laravel framework

## Installation

Run `composer require -W devocean/creditinfo` in the terminal. 

*For local development, please do the following.*
- Clone the repository using `git clone https://gitlab.com/devocean-pass/creditinfo.git`
- In your project's composer.json file, add;

```json
    "repositories": [
        {
            "type": "path",
            "url": "path-to-credit-info-folder"      
        }
    ] 
```
- Run `composer require -W devocean/creditinfo`.

## Publishing configurations

This package has variables that needs to be updated when live environment is available. These variables include endpoint url, username, password, soap action namespace e.t.c. Therefore, it's important to load config file into your laravel project.

In project's terminal window, run `php artisan vendor:publish`.

The file `creditinfo.php` will be generated in your project's config folder. 

## Running migrations

You are required to run migrations `php artisan migrate`.

## Usage

You can use the package as  follows;

```php
    $service = new CreditInfoSearchService();
    try {
       $scoresOrReport = $service->getReport(array $input, string $tag); 
        print_r($scoresOrReport);
    } 
    catch (Exception $e) {
        print_r($e->getMessage());
    }
```

### Inputs

The table below further describes the input parameters of `fn getReport(..., ...)`.

<table>
    <thead>
        <th>Allowed tags</th>
        <th>Allowed input array keys</th>
    </thead>
    <tbody>
        <tr>
            <td rowspan="3">company</td>
            <td> company-name
                <tr><td>company-registration-number</td></tr>
                <tr><td>tax-number</td></tr>
            </td>
        </tr>
        <tr>
            <td rowspan="8">individual</td>
            <td> first-name
                <tr><td>last-name</td></tr>
                <tr><td>full-name</td></tr>
                <tr><td>phone-number</td></tr>
                <tr><td>date-of-birth</td></tr>
                <tr><td>national-id</td></tr>
                <tr><td>voters-id</td></tr>
                <tr><td>tax-number</td></tr>
            </td>
        </tr>
    </tbody>
</table>

### Outputs

When calling `fn getReport(..., ...)`, the response is always a <strong>score e.g. 250</strong>.

### Exceptions

<table>
    <thead>
        <th>In</th>
        <th>Exception</th>
        <th>Description</th>
    </thead>
    <tbody>
        <tr>
            <td>Repository (Domain)</td>
            <td>METHOD_NOT_IMPLEMENTED</td>
            <td>Trying to use repository function when not implemented</td>
        </tr>
        <tr>
            <td rowspan='6'>Use Cases</td>
            <td>EMPTY_SEARCH_INPUT</td>
            <td>Flagged when input array is completely empty</td>
        </tr>
        <tr>
            <td>EMPTY_COMPANY_NAME</td>
            <td>Flagged when company name is not provided</td>
        </tr>
        <tr>
            <td>EMPTY_COMPANY_TIN</td>
            <td>Flagged when company tax identification number is not provided</td>
        </tr>
        <tr>
            <td>EMPTY_SEARCH_INPUT_ID</td>
            <td>Flagged when individual or company search input ID is not provided</td>
        </tr>
        <tr>
            <td>EMPTY_INDIVIDUAL_INPUT_ID</td>
            <td>Flagged when individual search input ID is not provided</td>
        </tr>
        <tr>
            <td>EMPTY_COMPANY_INPUT_ID</td>
            <td>Flagged when company search input ID is not provided</td>
        </tr>
        <tr>
            <td rowspan='4'>CreditInfoSearchService</td>
            <td>SUBJECT_NOT_FOUND</td>
            <td>When remote search does not return subject information or report</td>
        </tr>
        <tr>
            <td>MULTIPLE_SUBJECT_INFO_FOUND</td>
            <td>When remote search only returns multiple subject information (no report)</td>
        </tr>
        <tr>
            <td>REPORT_NOT_FOUND_FOR_SUBJECT</td>
            <td>When remote search doesn't find report for the applicant/subject</td>
        </tr>
        <tr>
            <td>INVALID_INPUT_TAG</td>
            <td>Flagged when input tag is incorrect</td>
        </tr>
    </tbody>
</table>




