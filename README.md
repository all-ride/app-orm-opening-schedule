# Ride app-orm-opening-schedule

This module provides a schedule model which can be hooked to any existing model.
It adds opening hours and holiday support.
There are some helper methods to easily look up opening hours and holidays.

## Example

```xml
<!-- application/config/models.xml -->

<!-- Add a schedule to an existing model -->
<model name="Foo">
    <field name="schedule" model="OpeningSchedule" relation="belongsTo">
        <option name="label.name" value="label.schedule" />
    </field>
</model>
```

```html
<!-- Template file -->

<!-- Using helper methods -->
{$schedule = {* get schedule from model entry *}}

<!-- Returns OpeningHour if open, else false -->
{$o = $schedule->getCurrentOpeningHour()}

<!-- OpeningHour methods -->
{if $o}
    <!-- Get timestamp -->
    {$o->getStart()->getTimestamp()}

    <!-- Create date -->
    {$o->getEnd()|date_format:"%A %d-%m-%Y %H:%M"}
{/if}

<!-- Get the next OpeningHour -->
{$o = $schedule->getNextOpeningHour()}

<!-- Get an OpeningHour by timestamp -->
{$o = $schedule->getOpeningHour($timestamp)}

<!-- Get the openinghours for a specific day of the week -->
{$o = $schedule->getOpeningHoursByDay(2)}

<!-- Returns Holiday or false -->
{$h = $schedule->getCurrentHoliday()}

<!-- Holiday methods -->
{if $h}
    <!-- Get timestamp -->
    {$h->getStart()->getTimestamp()}

    <!-- Create date -->
    {$h->getEnd()|date_format:"%A %d-%m-%Y %H:%M"}
{/if}

<!-- Get the next Holiday -->
{$h = $schedule->getNextHoliday()}

<!-- Get an OpeningHour by timestamp -->
{$h = $schedule->getHoliday($timestamp)}
```
