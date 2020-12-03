<?php

class Date {

    public static function getCurrentTimestamp(string $time_zone = 'UTC', string $format = 'Y-m-d H:i:s'): string {
        return (new DateTime('now', new DateTimeZone($time_zone)))->format($format);
    }

    public static function getCurrentDate(string $time_zone = 'UTC', string $format = 'Y-m-d'): string {
        return self::getCurrentTimestamp($time_zone, $format);
    }

    public static function convertUtcTimestampToTimeZone(string $date_time, string $time_zone, string $format = 'd-M-Y g:i a', string $modify_string = ''): string {
        $DT = new DateTime($date_time, new DateTimeZone('UTC'));
        if(!empty($modify_string)) {
            $DT->modify($modify_string);
        }
        $DT->setTimezone(new DateTimeZone($time_zone));
        return $DT->format($format);
    }

    public static function convertUtcDateToTimeZone(string $date, string $time_zone, string $format = 'd-M-Y', string $modify_string = ''): string {
        return self::convertUtcTimestampToTimeZone($date, $time_zone, $format, $modify_string);
	}
	
	/**
	 * Generates a key value array of time zone
	 * https://stackoverflow.com/a/17355238/721019
	 *
	 * @return array
	 */
	public static function generateTimeZoneArray(): array {
		$regions = array(
			DateTimeZone::AFRICA,
			DateTimeZone::AMERICA,
			DateTimeZone::ANTARCTICA,
			DateTimeZone::ASIA,
			DateTimeZone::ATLANTIC,
			DateTimeZone::AUSTRALIA,
			DateTimeZone::EUROPE,
			DateTimeZone::INDIAN,
			DateTimeZone::PACIFIC,
		);

		$timezones = array();
		foreach( $regions as $region )
		{
			$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
		}

		$timezone_offsets = array();
		foreach( $timezones as $timezone )
		{
			$tz = new DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
		}

		// sort timezone by offset
		asort($timezone_offsets);

		$timezone_list = array();
		foreach( $timezone_offsets as $timezone => $offset ) {
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate( 'H:i', abs($offset) );

			$pretty_offset = "UTC${offset_prefix}${offset_formatted}";

			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}

		return $timezone_list;
	}

}