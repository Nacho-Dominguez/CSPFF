<?
	/**
	* Holiday CLASS
	*
	* This class will determine if a date is a US holiday. It can also return the next business date.
	* Although made specifically for US holidays, non-US holidays could easily be checked for by adding them to the isHoliday method.
	*
	* @version 1.0
	* @author Floyd Resler <fresler@maccrafters.com>
	* @copyright Copyright 2010 Floyd Resler
	*
	*/
	
	class A25_Holiday {
		const DayAfterThanksgiving=1;
		const ChristmasEve=2;
		const GoodFriday=3;
		const Saturday=6;
		const Sunday=0;
		
		private $date="";
		private $options=array();
		
		/**
		* Class constructor
		* 
		* $date is the date you are checking. It can be in any format PHP understands.  The $options array
		* contains numbers to determine if certain dates should be considered a holiday. You can use the class constants of 
		* DayAfterThanksgiving, ChristmasEve, GoodFriday, Saturday and Sunday.  Whichever ones you pass in the array will be considered a holiday.
		* You can use two "short cut" values in this array. If you use "weekend" then both Saturday and Sunday will be included.  If you use "all"
		* then all the optional days will be included.
		*
		* @parameter string $date the date you are checking
		* @parameter array $options a list of options
		*
		* The example below will treat the day after Thanksgiving and Christmas Eve as a holiday.
		* <code>$holiday=new holiday("11/25/2010",array(holiday::DayAfterThanksgiving,holiday::ChristmasEve))</code>
		*/
		
		public function __construct($date,$options) {
			$this->date=$date;
			if(array_search("all",$options)!==false) {
				$options=array(self::Sunday,self::Saturday,self::DayAfterThanksgiving,self::ChristmasEve,self::GoodFriday);
			} elseif(array_search("weekend",$options)!==false) {
				$options[]=self::Saturday;
				$options[]=self::Sunday;
			}
			$this->options=$options;
			
			$stamp=strtotime($date);
			if($stamp===false) {
				throw new Exception("class.holiday.php could not understand the date of $date");
			}
		}
		
		/**
		* isHoliday
		*
		* Checks to see if the current date is a holiday.  If it is a holiday, the name of the holiday will be returned
		*
		* @parameter string $date an optional date to check other than the one passed in the class constructor.
		* 
		* @return string
		*/
		
		public function isHoliday($date="") {
			if(!$date) {
				$date=$this->date;
			}
			
			$stamp=strtotime($date);
			$month=date("m",$stamp);
			$day=date("d",$stamp);
			$year=date("Y",$stamp);
			$dayOfWeek=date("w",$stamp);
			$options=$this->options;
			
			$easter=easter_date($year); //Get Easter date for determining Good Friday
			
			//Weekend
			if(array_search($dayOfWeek,$options)!==false) {
				if($dayOfWeek==0) {
					$holiday="Sunday";
				} elseif($dayOfWeek==6) {
					$holiday="Saturday";
				}
			}
			
			//New Year's Day
			if($month==1 && $day==1) {
				$holiday="New Year's Day";
			}
			
			//Good Friday
			if(array_search($this->GoodFriday,$options)!==false) {
				$goodFriday=date("Y-m-d",strtotime(date("Y-m-d",$easter)." -2 days"));
				if($goodFriday=="$year-$month-$day") {
					$holiday="Good Friday";
				}
			}
			
			//Memorial Day
			if($dayOfWeek==1 && $day>24 && $month==5) { //Last Monday of May
				$holiday="Memorial Day";
			}
			
			//Independence Day
			if($month==7 && $day==4) {
				$holiday="Independence Day";
			}
			
			//Labor Day
			if($dayOfWeek==1 && $day<=7 && $month==9) { //First Monday of September
				$holiday="Labor Day";
			}
			
			//Thanksgiving
			//Thanksgiving is on the fourth Thursday of November so we have to do a little extra math
			if($dayOfWeek==4 && $month==11) {
				$weekNumber=floor($day/7);
				if($weekNumber==3) {
					$holiday="Thanksgiving";
				}
			}
			
			//Day After Thanksgiving
			//The day after Thanksgiving is on the fourth Friday of November so we have to do a little extra math
			if($dayOfWeek==5 && $month==11 && array_search($this->DayAfterThanksgiving,$options)!==false) {
				$weekNumber=floor($day/7);
				if($weekNumber==3) {
					$holiday="Day After Thanksgiving";
				}
			}
			
			//Christmas Eve
			if($day==24 && $month==12 && array_search($this->ChristmasEve,$options)!==false) {
				$holiday="Christmas Eve";
			}
			
			//Christmas
			if($day==25 && $month==12) {
				$holiday="Christmas";
			}
			return $holiday;
		}
		
		/**
		* getNextBusinessDate
		*
		* Returns the date of the next non-holiday date in Y-m-d format
		*
		* @return string
		*/

		public function getNextBusinessDate($date="") {
			if(!$date) {
				$date=$this->date;
			}
			$stamp=strtotime($date)+86400;
			while($this->isHoliday(date("Y-m-d",$stamp))) {
				$stamp+=86400;
			}
			return date("Y-m-d",$stamp);
		}
			
			
	}
?>
