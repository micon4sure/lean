<?
namespace lean\i18n;
use IntlDateFormatter, NumberFormatter;

/**
 * Formatter for dates, datetimes, times, numbers and currencies
 */
class Formatter
{
    /**
     * @var IntlDateFormatter
     */
    private $datetimeFormatter;
    /**
     * @var IntlDateFormatter
     */
    private $dateFormatter;
    /**
     * @var IntlDateFormatter
     */
    private $timeFormatter;
    /**
     * @var NumberFormatter
     */
    private $numberFormatter;
    /**
     * @var NumberFormatter
     */
    private $currencyFormatter;

    /**
     * @param \Locale $locale
     * @param \DateTimeZone $timezone
     */
    public function __construct($locale, $timezone)
    {
        // datetimeFormatter
        $this->datetimeFormatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::SHORT,
            $timezone,
            IntlDateFormatter::GREGORIAN
        );

        // dateFormatter
        $this->dateFormatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            $timezone,
            IntlDateFormatter::GREGORIAN
        );

        // timeFormatter
        $this->timeFormatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::NONE,
            IntlDateFormatter::SHORT,
            $timezone,
            IntlDateFormatter::GREGORIAN
        );

        // numberFormatter
        $this->numberFormatter = new NumberFormatter(
            $locale,
            NumberFormatter::DECIMAL
        );

        // currencyFormatter
        $this->currencyFormatter = new NumberFormatter(
            $locale,
            NumberFormatter::CURRENCY
        );
    }

    /**
     * Format a datetime
     * @param $datetime
     * @return string
     */
    public function formatDateTime($datetime)
    {
        return $this->datetimeFormatter->format($datetime);
    }

    /**
     * Format a date
     * @param $datetime
     * @return string
     */
    public function formatDate($datetime)
    {
        return $this->dateFormatter->format($datetime);
    }

    /**
     * Format a time
     * @param $datetime
     * @return string
     */
    public function formatTime($datetime)
    {
        return $this->timeFormatter->format($datetime);
    }

    /**
     * Format a number
     * @param $number
     * @return string
     */
    public function formatNumber($number)
    {
        return $this->numberFormatter->format($number);
    }

    /**
     * Format a currency
     * @param $currency
     * @return string
     */
    public function formatCurrency($currency)
    {
        return $this->currencyFormatter->format($currency);
    }
}