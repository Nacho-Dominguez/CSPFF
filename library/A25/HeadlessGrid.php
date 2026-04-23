<?php
class A25_HeadlessGrid extends A25_Grid
{
	private $columnCss = array();

	public function setColumnCss($column_name, $css)
	{
		$this->columnCss[$column_name] = $css;
	}
	
	public function generate()
	{
		if (!$this->_dataObjects[0])
			return '';

		$return .= '<table class="adminlist" cellspacing="0" style="width:100%">';

		$k = 0;
		foreach ($this->_dataObjects as $object) {
			$return .= "<tr class='row$k'>";
			foreach ($object as $key => $value) {
				$return .= $this->cell($value, $key);
			}
			$return .= '</tr>';
			$k = 1 - $k;
		}

		$return .= '</table>';

		return $return;
	}
	protected function cell($value, $key = null)
	{
		$return = '<td';
		if (array_key_exists($key, $this->columnCss))
			$return .= ' style="' . $this->columnCss[$key] . '"';
		$return .= ">$value</td>";
		return $return;
	}
}
