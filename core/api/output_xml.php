<?php

class Output_xml extends API_Output implements api_output_interface
{
	private $output_contents;

	/**
	 * Constructor sets the abstract class output type to XML
	 */
	public function __Construct()
	{
		$this->output_type = 'xml';
	}

	/**
	 * Builds the error feedback message specific to XML
	 * 
	 * @param int $status
	 * @param string $message
	 * @return XML
	 */
	public static function build_error($status, $message="")
	{
		$xml = new SimpleXMLElement('<xml/>');
		$xml->addChild('status', $status);
		$xml->addChild('error', $message);
		
		$output = $xml->asXML();

		return $output;
	}

	/**
	 * Builds up the output response for the API
	 * 
	 * @param string $content
	 * @param int $status
	 */
	public function build_output_contents($content, $status)
	{
		$output = array();

		$output['status'] = $status;

		$output['message'] = $this->status_codes[$status];

		// If the data isnt an associated array need to make it one
		// so the XML formats!
		if ($content[0] == "") {
			$result = array();
			$result[] = $content;
			$content = $result;
		}

		$output['data'] = $content;

		$this->output_contents = $this->build_xml($output);

		$this->display_output();
	}

	/**
	 * Produces the XML to display back
	 * 
	 * @param array $output
	 * @return xml
	 */
	protected function build_xml($output)
	{
		// Building up two XML's - one holds all the data listed, and 
		// the other holds the status information.
		// Need the data seperate to access it directly as an array key
		$xml = new SimpleXMLElement('<xml/>');
		
		$xml->addChild('status', $output['status']);
		$xml->addChild('message', $output['message']);

		// Build up the data XML as a seperate XML
		// Appending all the data items 
		$subxml = new SimpleXMLElement('<data/>');
		
		foreach ($output['data'] as $data) {

			$item = $subxml->addChild('item');	
				
			foreach ($data as $key => $value) {

				if (!is_numeric($key)) {
					$item->addChild($key, (!!$value?$value:"null"));	
				}
			}
			
		}

		// merge the output
		$result = $this->merge_xml($subxml->asXML(), $xml->asXML());

		return $result;
	}

	/**
	 * Merges two XMLs into one to format data listings
	 * 
	 * @param object|XML $one
	 * @param object|XML $two
	 * 
	 * @return object|xml
	 */
	protected function merge_xml($one, $two)
	{
		// Merge the two XML documents into one
		$orgdoc = new DOMDocument;
		$orgdoc->loadXML($one);

		// Setting the data node to be the node with the listing of data in
		$node = $orgdoc->getElementsByTagName("data")->item(0);
		$orgdoc->saveXML();

		// Setup the next XML ready to merge
		$newdoc = new DOMDocument;
		$newdoc->formatOutput = true;
		$newdoc->loadXML($two);
		$newdoc->saveXML();

		// Import the node into the new XML document
		$node = $newdoc->importNode($node, true);
		$newdoc->documentElement->appendChild($node);

		return $newdoc->saveXML();
	}

	/**
	 * Displays the API result content
	 */
	public function display_output()
	{
		header("Content-type: application/xml", true);

		exit ($this->output_contents);
	}
}

?>