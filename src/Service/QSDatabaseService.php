<?php

declare(strict_types=1);
/**
 * @file QSDatabaseService.php
 * @author Stephan Hombergs <info@sah-company.com>
 */

/**
 * Class QSDatabaseServiceClient
 *
 * Provides functionality for interacting with the QS database web service.
 *
 * @package Thing
 */
class QSDatabaseServiceClient
{
	private SoapClient $client;

	/**
	 * Initializes a new instance of the SoapClient class.
	 *
	 * @param string $wsdl The URL to the WSDL file. Example: 'https://www.q-s.de/services/files/datenbank/schlachtbetriebe/open_access_akt.wsdl'.
	 * @param array $options An array of options to pass to the SoapClient.
	 */
	public function __construct(string $wsdl)
	{
		$this->client = new SoapClient($wsdl, ['trace' => true, 'exceptions' => true]);
	}
	/**
	 * Queries the QS database for certifications.
	 *
	 * @param string $locationId The ID of the location to query. Aka VVVO.
	 * @param string|null $btartId The ID of the business type (optional). e.g. "2001"
	 * @return stdClass
	 */
	public function getCertifications(string $locationId, ?string $btartId = null): stdClass
	{
		$params = ['locationId' => $locationId];
		if ($btartId !== null) {
			$params['btartId'] = $btartId;
		}
		$response = $this->client->selectQSTW($params);

		// Adjust the return as needed based on actual response structure
		return $response;
	}
}
