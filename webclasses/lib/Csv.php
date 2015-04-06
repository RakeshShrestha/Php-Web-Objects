<?php
/*
$csvmsg['content_headers'] = $csv->load(upload_file, true);

if (is_array($csvmsg['content_headers']) && (count($csvmsg['content_headers']) == 5) )
{				
	$csvmsg['content'] = $csv->load(upload_file);

	foreach ($csvmsg['content'] as $key => $val)
	{	
		$i++;
		$id = $val['id'];
	}
}
				
*/

class Csv
{
	private $enclosure;
	private $delimiter;
	private $rows;	
	private $numfields;

	public function __construct()
	{
		parent::__construct();
		
		$this->enclosure = "\"";
		$this->delimiter = ",";
		$this->rows = array();		
		$this->numfields = 0;
	}
	
	public function load ($file, $headersonly = false)
	{
		$ext = strtolower(strrchr($file, '.'));
		
		if ($ext == '.csv' || $ext == '.txt')
		{
			$row = 1;
			$handle = fopen($file, 'r');

			if ($ext == '.txt')
				$this->delimiter = "\t";
	 
			while (($data = fgetcsv($handle, 1000, $this->delimiter, $this->enclosure)) !== FALSE)
			{
				if ($row == 1)
				{
					foreach ($data as $key => $val)
						$headingTexts[] = strtolower(trim($val));
					
					$this->numfields = count($headingTexts);
				}
				
				if ($this->numfields < 2)
				{
					fclose($handle);
					return 0;
				}
				
				if ($headersonly)
				{
					fclose($handle);
					return $headingTexts;
				}
				
				if ($row > 1 )
				{
					foreach ($data as $key => $value)
					{
						unset($data[$key]);				   
						$data[strtolower($headingTexts[$key])] = $value;
					}
					$this->rows[] = $data;
				}
				$row++;
			}
			fclose($handle);
		}
		
		return $this->rows;
	}

	public function write($csv_delimiter, array $csv_headers_array, array $csv_write_res)
	{ 
		if (!isset($csv_delimiter))
		{
			$csv_delimiter = $this->delimiter;
		}
				
		$data = "";
		$data_temp = '';
		foreach ($csv_headers_array AS $val)
		{			
			$data_temp .= $this->enclosure . strtolower($val) . $this->enclosure . $csv_delimiter;			
		}
		$data .= rtrim( $data_temp, $csv_delimiter ) . "\r\n";
		
		echo $data;

		$data = "";
		$data_temp = '';
		foreach( $csv_write_res AS $val )
		{			
			$data_temp = '';
			foreach( $val as $val2 )
			{
				$data_temp .= $this->enclosure . $val2 . $this->enclosure . $csv_delimiter;
			}
			
			$data .= rtrim( $data_temp, $csv_delimiter ) . "\r\n";
		}
		echo $data;
	}
	
}
