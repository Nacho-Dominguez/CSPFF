<?php
class A25_Grid
{
	protected $_dataObjects;

	/**
	 *
	 * @param array $dataObjects - the objects to show in the grid
	 * @param array $fields - the fields to show for each object, in the order
	 * that they should be shown
	 */
	public function __construct(array $dataObjects)
	{
		$this->_dataObjects = $dataObjects;
	}
	
	public function generate()
	{
		if (!$this->_dataObjects[0])
			return '';

		$return .= '<table class="adminlist" style="width:100%">';

		$return .= $this->heading();

		$k = 0;
		foreach ($this->_dataObjects as $object) {
			$return .= "<tr class='row$k'>";
			foreach ($object as $key => $value) {
				$return .= "<td>$value</td>";
			}
			$return .= '</tr>';
			$k = 1 - $k;
		}

		$return .= '</table>';

		return $return;
	}
	private function heading()
	{
		$return = '<thead><tr>';
		foreach ($this->_dataObjects[0] as $key => $value) {
			$return .= "<th style='text-align: left'>$key</th>";
		}
		$return .= '</tr></thead>';
		return $return;
	}
}
?>
