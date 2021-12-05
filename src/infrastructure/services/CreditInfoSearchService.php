<?php

namespace Devocean\Creditinfo\infrastructure\services;

use Devocean\Creditinfo\app\services\SearchManager;
use Devocean\Creditinfo\domain\entities\SearchInputCompany;
use Devocean\Creditinfo\domain\entities\SearchInputIndividual;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Psr\Http\Client\ClientExceptionInterface;
use Ramsey\Uuid\Uuid; 
use SimpleXMLElement;

class CreditInfoSearchService extends SearchManager
{
    /**
     * @param array $searchInput array of search inputs
     * @param string $inputTag company | individual
     * @throws Exception|ClientExceptionInterface
     */
    public function getReport(array $searchInput, string $inputTag): array
    {
       if ($inputTag == 'company') {
            return $this->getCompanyReport($searchInput);
        }
        if ($inputTag == 'individual') {
            return $this->getIndividualReport($searchInput);
        }
        throw new Exception('INVALID_INPUT_TAG');
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    #[ArrayShape(["error" => "bool", "description" => "string"])]
    private function getIndividualReport(array $searchInput): array
    {
        $individual = new SearchInputIndividual(
            $searchInput["first-name"] ?? '',
            $searchInput["last-name"] ?? '',
            $searchInput["full-name"] ?? '',
            $searchInput["phone-number"] ?? '',
            $searchInput["date-of-birth"] ?? '',
            $searchInput["national-id"] ?? '',
            $searchInput["voters-id"] ?? '',
            $searchInput["tax-number"] ?? ''
        );
        try {
            return $this->getRemoteReport($individual);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    private function getCompanyReport(array $searchInput): array
    {
        $company = new SearchInputCompany(
            $searchInput["company-registration-number"] ?? '',
            $searchInput["company-name"] ?? '',
            $searchInput["tax-number"] ?? ''
        );
        try {
            return $this->getRemoteReport($company);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    #[ArrayShape(['score' => "\int|mixed", 'report' => "array"])]
    private function getRemoteReport(mixed $data): array
    {
        $requestData = $data instanceof SearchInputCompany
            ? $this->buildCompanyRequestData($data)
            : $this->buildIndividualRequestData($data);

        $client = new Client(['base_uri' => $_ENV['BASE_URI']]);
        $headers = [ 'Content-Type' => 'text/xml', 'SOAPAction' => $_ENV['ACTION'] ];
        $body = $this->buildRequestBody($requestData);
        $request = new Request('POST', $_ENV['URI'], $headers, $body);
        $response = $client->sendRequest($request)->getBody();
        return $this->parseXMLResponseAndGetRecords($response);
    }

    /**
     * @throws Exception
     */
    #[ArrayShape(['score' => "int|mixed", 'report' => "array"])]
    private function parseXMLResponseAndGetRecords(string $xml): array
    {
        $stripped = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $simpleXML = new SimpleXMLElement($stripped);
        $body = $simpleXML->xpath('//sBody')[0];
        $dataArray = json_decode(json_encode((array)$body), true);
        $connector = $dataArray['QueryResponse']['QueryResult']['ResponseXml']['response']['connector'];

        if (isset($connector['notFound'])) {
            throw new Exception('SUBJECT_NOT_FOUND');
        }

        $multiHit = $connector['data']['response']['MultiHit'];
        if (isset($multiHit) && $multiHit['message'] == 'SingleHit + Subject\'s info') {
            throw new Exception('MULTIPLE_SUBJECT_INFO_FOUND');
        }

        $report = $connector['data']['response']['CustomReport'];
        if (isset($report)) {
            return [
                'score' => $report['Dashboard']['CIP']['Score'] ?? 0,
                'report' => $report
            ];
        }
        throw new Exception('REPORT_NOT_FOUND_FOR_SUBJECT');
    }

    #[ArrayShape(["request" => "array"])]
    private function buildRequestBody(string $requestData): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
    <s:Header>
        <wsse:Security s:mustUnderstand="1"
            xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <wsse:UsernameToken wsu:Id="SecurityToken-ad2b9f33-eba3-4e0f-ae41-e90379b97f56"
                xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <wsse:Username>'. $_ENV['USERNAME'] .'</wsse:Username>
                <wsse:Password>'. $_ENV['PASSWORD'] .'</wsse:Password>
            </wsse:UsernameToken>
        </wsse:Security>
    </s:Header>
    <s:Body>
        <Query xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector">
            <request xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                <MessageId>'. Uuid::uuid4() .'</MessageId>
                <RequestXml>
                    <RequestXml xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Messages/Request">
                        <connector id="22636b5b-067f-48ae-86f6-cf6a900b7408">
                            <data id="'. Uuid::uuid4() .'">
                                <request
                                    xmlns="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/Bee/Request"
                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                    xsi:schemaLocation="http://creditinfo.com/schemas/2012/09/MultiConnector/Connectors/Bee/Request">
                                    <DecisionWorkflow>'. $_ENV['DECISION_WORKFLOW'] .'
                                    </DecisionWorkflow>
                                    <RequestData>'. $requestData .'</RequestData>
                                </request>
                            </data>
                        </connector>
                    </RequestXml>
                </RequestXml>
                <Timeout i:nil="true" />
            </request>
        </Query>
    </s:Body>
</s:Envelope>';
}

#[Pure]
private function buildCompanyRequestData(SearchInputCompany $company): string
{
return '<Company>
    '. $this->getCompanyNameXML($company->getCompanyName()) .'
    <IdNumbers>
        '. $this->getTinXML($company->getTIN(), 'company') .'
        '. $this->getRegistrationNumberXML($company->getRegistrationNumber()) .'
    </IdNumbers>
    <InquiryReasons>'. $_ENV['APPLICATION_FOR_CREDIT_OR_AMENDMENT_OF_CREDIT_TERMS'] .'
    </InquiryReasons>
    <TypeOfReport>'. $_ENV['CREDIT_INFO_REPORT_PLUS'] .'</TypeOfReport>
</Company>';
}

#[Pure]
private function buildIndividualRequestData(SearchInputIndividual $individual): string
{
return '<Individual>
    '. $this->getDOBXml($individual->getDob()) .'
    '. $this->getFirstNameXML($individual->getFirstName()) .'
    '. $this->getFullNameXML($individual->getFullName()) .'
    <IdNumbers>
        '. $this->getTinXML($individual->getTIN(), 'individual') .'
        '. $this->getVotersIdXML($individual->getVotersId()) .'
        '. $this->getNinXML($individual->getNIN()) .'
    </IdNumbers>
    '. $this->getPhoneNumberXML($individual->getPhoneNumber()) .'
    '. $this->getLastNameXML($individual->getLastName()) .'
    <InquiryReasons>'.$_ENV['APPLICATION_FOR_CREDIT_OR_AMENDMENT_OF_CREDIT_TERMS'].'
    </InquiryReasons>
    <TypeOfReport>'.$_ENV['CREDIT_INFO_REPORT_PLUS'].'</TypeOfReport>
</Individual>
';
}

private function getTinXML(string $tin, string $tag): string
{
if (!empty($tin)) {
return ($tag == 'company') ?
"
<IdNumberPairCompany>
    <IdNumber>$tin</IdNumber>
    <IdNumberType>TaxNumber</IdNumberType>
</IdNumberPairCompany>
" :
"
<IdNumberPairIndividual>
    <IdNumber>$tin</IdNumber>
    <IdNumberType>TaxNumber</IdNumberType>
</IdNumberPairIndividual>
";
}
return '';
}

private function getRegistrationNumberXML(string $registrationNumber): string
{
return !empty($registrationNumber) ? "
<IdNumberPairCompany>
    <IdNumber>$registrationNumber</IdNumber>
    <IdNumberType>RegistrationNumber</IdNumberType>
</IdNumberPairCompany>
" : "";
}

private function getCompanyNameXML(string $companyName): string
{
return !empty($companyName) ? "<CompanyName>$companyName</CompanyName>" : "";
}

private function getDOBXml(string $dob): string
{
return !empty($dob) ? "<DateOfBirth>$dob</DateOfBirth>" : "";
}

private function getFirstNameXML(string $firstName): string
{
return !empty($firstName) ? "<FirstName>$firstName</FirstName>" : "";
}

private function getLastNameXML(string $lastName): string
{
return !empty($lastName) ? "<PresentSurname>$lastName</PresentSurname>" : "";
}

private function getFullNameXML(string $fullName): string
{
return !empty($fullName) ? "<FullName>$fullName</FullName>" : "";
}

private function getVotersIdXML(string $votersId): string
{
return !empty($votersId) ? "
<IdNumberPairIndividual>
    <IdNumber>$votersId</IdNumber>
    <IdNumberType>VotersID</IdNumberType>
</IdNumberPairIndividual>
" : "";
}

private function getNinXML(string $nin): string
{
return !empty($nin) ? "
<IdNumberPairIndividual>
    <IdNumber>$nin</IdNumber>
    <IdNumberType>NationalID</IdNumberType>
</IdNumberPairIndividual>
" : "";
}

private function getPhoneNumberXML(string $phoneNumber): string
{
return !empty($phoneNumber) ? "
<PhoneNumbers>
    <string>$phoneNumber</string>
</PhoneNumbers>
" : "";
}
}